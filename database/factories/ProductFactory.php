<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $name = $this->faker->unique()->word();
        $description = $this->faker->sentence();
        $price = $this->faker->randomFloat(2, 1, 100);

        return [
            'name' => $name,
            'description' => $description,
            'price' => $price,
        ];
    }
}
