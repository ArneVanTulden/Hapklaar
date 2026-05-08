<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'arne.zeekoe@gmail.com')->first();

        if (! $user) {
            return;
        }

        $inventory = Inventory::firstOrCreate(['user_id' => $user->id]);

        if ($inventory->items()->exists()) {
            return;
        }

        $ingredient = fn(string $name) => Ingredient::where('canonical_name', $name)->value('id');

        $items = [
            // Fridge
            ['name' => 'Melk',             'quantity' => 1,   'unit' => 'liter',  'location' => 'fridge',   'ingredient_id' => $ingredient('Melk')],
            ['name' => 'Griekse yoghurt',  'quantity' => 500, 'unit' => 'g',      'location' => 'fridge',   'ingredient_id' => $ingredient('Griekse yoghurt')],
            ['name' => 'Kipfilet',         'quantity' => 400, 'unit' => 'g',      'location' => 'fridge',   'ingredient_id' => $ingredient('Kipfilet')],
            ['name' => 'Spinazie',         'quantity' => 300, 'unit' => 'g',      'location' => 'fridge',   'ingredient_id' => $ingredient('Spinazie')],
            ['name' => 'Eieren',           'quantity' => 6,   'unit' => 'stuks',  'location' => 'fridge',   'ingredient_id' => $ingredient('Eieren')],
            ['name' => 'Oude kaas',        'quantity' => 200, 'unit' => 'g',      'location' => 'fridge',   'ingredient_id' => null],
            ['name' => 'Rode paprika',     'quantity' => 2,   'unit' => 'stuks',  'location' => 'fridge',   'ingredient_id' => $ingredient('Paprika')],
            ['name' => 'Boter',            'quantity' => 250, 'unit' => 'g',      'location' => 'fridge',   'ingredient_id' => $ingredient('Boter')],

            // Freezer
            ['name' => 'Kippendijen',      'quantity' => 600, 'unit' => 'g',      'location' => 'freezer',  'ingredient_id' => null],
            ['name' => 'Brood',            'quantity' => 1,   'unit' => 'stuk',   'location' => 'freezer',  'ingredient_id' => null],
            ['name' => 'Diepvries erwten', 'quantity' => 400, 'unit' => 'g',      'location' => 'freezer',  'ingredient_id' => $ingredient('Doperwten')],
            ['name' => 'Tonijnfilet',      'quantity' => 2,   'unit' => 'stuks',  'location' => 'freezer',  'ingredient_id' => $ingredient('Tonijn')],

            // Pantry
            ['name' => 'Penne pasta',      'quantity' => 500, 'unit' => 'g',      'location' => 'pantry',   'ingredient_id' => $ingredient('Penne')],
            ['name' => 'Basmati rijst',    'quantity' => 1,   'unit' => 'kg',     'location' => 'pantry',   'ingredient_id' => $ingredient('Basmatirijst')],
            ['name' => 'Tomatenblokjes',   'quantity' => 2,   'unit' => 'blikken','location' => 'pantry',   'ingredient_id' => $ingredient('Tomatenblokjes')],
            ['name' => 'Olijfolie',        'quantity' => 750, 'unit' => 'ml',     'location' => 'pantry',   'ingredient_id' => $ingredient('Olijfolie')],
            ['name' => 'Knoflook',         'quantity' => 1,   'unit' => 'bol',    'location' => 'pantry',   'ingredient_id' => $ingredient('Knoflook')],
            ['name' => 'Rode linzen',      'quantity' => 500, 'unit' => 'g',      'location' => 'pantry',   'ingredient_id' => $ingredient('Rode linzen')],
            ['name' => 'Sojasaus',         'quantity' => 200, 'unit' => 'ml',     'location' => 'pantry',   'ingredient_id' => $ingredient('Sojasaus')],
        ];

        foreach ($items as $item) {
            $inventory->items()->create($item);
        }
    }
}
