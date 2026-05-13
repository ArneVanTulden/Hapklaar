<?php

namespace Database\Seeders;

use App\Models\DietTag;
use Illuminate\Database\Seeder;

class DietTagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Vegetarisch', 'Halal', 'Lactosevrij', 'Glutenvrij'] as $name) {
            DietTag::firstOrCreate(['name' => $name]);
        }
    }
}
