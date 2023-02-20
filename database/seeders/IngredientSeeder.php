<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingredient::create([
            'name' => 'Beef',
            'stock' => 500,
            'unit_id' => 1
        ]);

        Ingredient::create([
            'name' => 'Cheese',
            'stock' => 500,
            'unit_id' => 1
        ]);

        Ingredient::create([
            'name' => 'Onion',
            'stock' => 500,
            'unit_id' => 11
        ]);
    }
}
