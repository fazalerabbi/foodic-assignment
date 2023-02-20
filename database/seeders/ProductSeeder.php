<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Burger',
            'description' => 'A delicious burger with beef, cheese and onion.',
            'price' => 10.99
        ]);
    }
}
