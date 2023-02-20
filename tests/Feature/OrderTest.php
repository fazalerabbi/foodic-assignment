<?php

namespace Tests\Feature;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_created_with_valid_data()
    {
        $product = Product::factory()->create([
            'name' => 'Burger',
            'price' => 10,
        ]);

        $ingredient1 = Ingredient::factory()->create([
            'name' => 'Beef',
            'stock' => 1000,
        ]);
        $ingredient2 = Ingredient::factory()->create([
            'name' => 'Cheese',
            'stock' => 70,
        ]);
        $ingredient3 = Ingredient::factory()->create([
            'name' => 'Onion',
            'stock' => 100,
        ]);

        $product->ingredients()->attach($ingredient1, ['quantity' => 150]);
        $product->ingredients()->attach($ingredient2, ['quantity' => 30]);
        $product->ingredients()->attach($ingredient3, ['quantity' => 20]);

        $user = User::factory()->create();

        $orderData = [
            'user_id' => $user->id,
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ];
        $response = $this->postJson('/api/v1/order', $orderData);


        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'total_price' => 20,
        ]);

        $this->assertDatabaseHas('order_product', [
            'order_id' => 1,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Beef',
            'stock' => 700, // 1000 - 2*150 = 700
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Cheese',
            'stock' => 10, // 5000 - 2*30 = 4940
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Onion',
            'stock' => 60, // 1000 - 2*20 = 960
        ]);
    }

    public function test_order_posted_empty_body()
    {
        $response = $this->postJson('/api/v1/order', []);
        $response->assertStatus(422);
        $responseBody = json_decode($response->getContent());

        $this->assertEquals('The user id field is required.', $responseBody->errors->user_id[0]);
        $this->assertEquals('The products field is required.', $responseBody->errors->products[0]);

    }

    public function test_order_post_invalid_product_id_user_id()
    {
        $response = $this->postJson('/api/v1/order', [
            'user_id' => 1,
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 2,
                ],
            ],
        ]);
        $response->assertStatus(422);
        $responseBody = json_decode($response->getContent());
        $productIndex = 'products.0.product_id';

        $this->assertEquals('The selected user id is invalid.', $responseBody->errors->user_id[0]);
        $this->assertEquals('The selected products.0.product_id is invalid.', $responseBody->errors->$productIndex[0]);

    }

    public function test_order_post_out_of_stock()
    {
        $product = Product::factory()->create([
            'name' => 'Burger',
            'price' => 10,
        ]);

        $ingredient1 = Ingredient::factory()->create([
            'name' => 'Beef',
            'stock' => 1000,
        ]);
        $ingredient2 = Ingredient::factory()->create([
            'name' => 'Cheese',
            'stock' => 70,
        ]);
        $ingredient3 = Ingredient::factory()->create([
            'name' => 'Onion',
            'stock' => 100,
        ]);

        $product->ingredients()->attach($ingredient1, ['quantity' => 150]);
        $product->ingredients()->attach($ingredient2, ['quantity' => 30]);
        $product->ingredients()->attach($ingredient3, ['quantity' => 20]);

        $user = User::factory()->create();

        $orderData = [
            'user_id' => $user->id,
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ];
        $response = $this->postJson('/api/v1/order', $orderData);


        $response->assertStatus(500);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_product', 0);

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Beef',
            'stock' => 1000,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Cheese',
            'stock' => 70,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Onion',
            'stock' => 100,
        ]);
    }

    public function test_order_can_be_created_with_multiple_products()
    {
        $product1 = Product::factory()->create([
            'name' => 'Burger - 1',
            'price' => 10,
        ]);
        $product2 = Product::factory()->create([
            'name' => 'Burger - 2',
            'price' => 10,
        ]);

        $ingredient1 = Ingredient::factory()->create([
            'name' => 'Beef',
            'stock' => 1000,
        ]);
        $ingredient2 = Ingredient::factory()->create([
            'name' => 'Cheese',
            'stock' => 70,
        ]);
        $ingredient3 = Ingredient::factory()->create([
            'name' => 'Onion',
            'stock' => 100,
        ]);

        $product1->ingredients()->attach($ingredient1, ['quantity' => 50]);
        $product1->ingredients()->attach($ingredient2, ['quantity' => 30]);
        $product1->ingredients()->attach($ingredient3, ['quantity' => 20]);


        $product2->ingredients()->attach($ingredient1, ['quantity' => 150]);
        $product2->ingredients()->attach($ingredient2, ['quantity' => 40]);
        $product2->ingredients()->attach($ingredient3, ['quantity' => 30]);

        $user = User::factory()->create();

        $orderData = [
            'user_id' => $user->id,
            'products' => [
                [
                    'product_id' => $product1->id,
                    'quantity' => 1,
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => 1,
                ],
            ],
        ];
        $response = $this->postJson('/api/v1/order', $orderData);


        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'total_price' => 20,
        ]);

        $this->assertDatabaseHas('order_product', [
            'order_id' => 1,
            'product_id' => $product1->id,
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('order_product', [
            'order_id' => 1,
            'product_id' => $product2->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Beef',
            'stock' => 800,
            'alert_sent' => 0,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Cheese',
            'stock' => 0, // 5000 - 2*30 = 4940
            'alert_sent' => 1,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name' => 'Onion',
            'stock' => 50, // 1000 - 2*20 = 960
            'alert_sent' => 1,
        ]);
    }
}

