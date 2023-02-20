<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition()
    {
        $unit = Unit::factory()->create();
        $name = $this->faker->unique()->word();
        $stock = 500;

        return [
            'name' => $name,
            'stock' => $stock,
            'unit_id' => $unit->id,
        ];
    }
}
