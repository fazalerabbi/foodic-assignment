<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Milligram', 'abbreviation' => 'mg'],
            ['name' => 'Pound', 'abbreviation' => 'lb'],
            ['name' => 'Ounce', 'abbreviation' => 'oz'],
            ['name' => 'Liter', 'abbreviation' => 'l'],
            ['name' => 'Milliliter', 'abbreviation' => 'ml'],
            ['name' => 'Cup', 'abbreviation' => 'cup'],
            ['name' => 'Teaspoon', 'abbreviation' => 'tsp'],
            ['name' => 'Tablespoon', 'abbreviation' => 'tbsp'],
            ['name' => 'Piece', 'abbreviation' => 'pc'],
        ];

        Unit::insert($units);
    }
}
