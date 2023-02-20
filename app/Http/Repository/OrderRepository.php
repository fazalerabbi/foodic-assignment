<?php

namespace App\Http\Repository;

use App\Jobs\SendEmail;
use App\Mail\StockIsLowMail;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly Order $order,
        private readonly Product $product,
        private readonly Ingredient $ingredient) {}

    public function create(array $orderData) : Order | null | string
    {
        // Calculate the total price of the order
        $totalPrice = 0;
        $productsData = collect($orderData['products'])->map(function ($productData) use (&$totalPrice) {
            $product = $this->product->with('ingredients')->findOrFail($productData['product_id']);
            $productData['name'] = $product->name;
            $productData['price'] = $product->price;
            $productData['ingredients'] = $product->ingredients->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'quantity' => $ingredient->pivot->quantity,
                ];
            });
            $totalPrice += $productData['quantity'] * $productData['price'];
            return $productData;
        });

        // Start a database transaction
        \DB::beginTransaction();

        try {
            // Create the order
            $this->order->total_price = $totalPrice;
            $this->order->user_id = $orderData['user_id'];
            $this->order->save();

            // Reduce the stock of the ingredients for each product in the order
            $ingredientsData = $productsData->flatMap(function ($productData) {
                return $productData['ingredients']->map(function ($ingredientData) use ($productData) {
                    $ingredientData['quantity'] *= $productData['quantity'];
                    return $ingredientData;
                });
            });
            $ingredientsData->each(function ($ingredientData) {
                $ingredient = $this->ingredient->findOrFail($ingredientData['id']);

                if ($ingredient->stock < $ingredientData['quantity']) {
                    throw new \Exception('Not enough stock for ingredient ' . $ingredient->name);
                }

                $ingredient->stock -= $ingredientData['quantity'];
                $ingredient->save();

                // Send an email if the stock is below 50%
                if ($ingredient->stock <= $ingredient->low_stock_threshold && !$ingredient->alert_sent) {
                    if (env('APP_ENV') !== 'testing') {
                        SendEmail::dispatch(config('mail.admin'), $ingredient->name);
                    }

                    $ingredient->alert_sent = true;
                    $ingredient->save();
                }
            });

            //attach the products to the order and save the quantity of each product
            $this->order->products()->attach($productsData->mapWithKeys(function ($productData) {
                return [$productData['product_id'] => ['quantity' => $productData['quantity']]];
            })->toArray());

            // Commit the database transaction
            \DB::commit();
            return $this->order;
        } catch (\Exception $e) {
            // Roll back the database transaction
            \Log::error($e->getMessage());
            \DB::rollBack();
            return $e->getMessage();
        }

        return null;
    }
}
