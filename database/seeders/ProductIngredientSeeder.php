<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use Illuminate\Database\Seeder;

class ProductIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $burger = Product::where('name', 'Burger')->first();

        $beef = Ingredient::where('name', 'Beef')->first();
        $cheese = Ingredient::where('name', 'Cheese')->first();
        $onion = Ingredient::where('name', 'Onion')->first();

        ProductIngredient::create([
            'product_id' => $burger->id,
            'ingredient_id' => $beef->id,
            'quantity' => 150
        ]);

        ProductIngredient::create([
            'product_id' => $burger->id,
            'ingredient_id' => $cheese->id,
            'quantity' => 30
        ]);

        ProductIngredient::create([
            'product_id' => $burger->id,
            'ingredient_id' => $onion->id,
            'quantity' => 20
        ]);
    }
}
