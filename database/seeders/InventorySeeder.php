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

        $items = [
            ['ingredient' => 'Melk',            'quantity' => 1,   'unit' => 'liter',   'location' => 'fridge'],
            ['ingredient' => 'Griekse yoghurt', 'quantity' => 500, 'unit' => 'g',       'location' => 'fridge'],
            ['ingredient' => 'Kipfilet',        'quantity' => 400, 'unit' => 'g',       'location' => 'fridge'],
            ['ingredient' => 'Spinazie',        'quantity' => 300, 'unit' => 'g',       'location' => 'fridge'],
            ['ingredient' => 'Eieren',          'quantity' => 6,   'unit' => 'stuks',   'location' => 'fridge'],
            ['ingredient' => 'Boter',           'quantity' => 250, 'unit' => 'g',       'location' => 'fridge'],
            ['ingredient' => 'Paprika',         'quantity' => 2,   'unit' => 'stuks',   'location' => 'fridge'],
            ['ingredient' => 'Kippendijen',     'quantity' => 600, 'unit' => 'g',       'location' => 'freezer'],
            ['ingredient' => 'Doperwten',       'quantity' => 400, 'unit' => 'g',       'location' => 'freezer'],
            ['ingredient' => 'Tonijn',          'quantity' => 2,   'unit' => 'stuks',   'location' => 'freezer'],
            ['ingredient' => 'Penne',           'quantity' => 500, 'unit' => 'g',       'location' => 'pantry'],
            ['ingredient' => 'Basmatirijst',    'quantity' => 1,   'unit' => 'kg',      'location' => 'pantry'],
            ['ingredient' => 'Tomatenblokjes',  'quantity' => 2,   'unit' => 'blikken', 'location' => 'pantry'],
            ['ingredient' => 'Olijfolie',       'quantity' => 750, 'unit' => 'ml',      'location' => 'pantry'],
            ['ingredient' => 'Knoflook',        'quantity' => 1,   'unit' => 'bol',     'location' => 'pantry'],
            ['ingredient' => 'Rode linzen',     'quantity' => 500, 'unit' => 'g',       'location' => 'pantry'],
            ['ingredient' => 'Sojasaus',        'quantity' => 200, 'unit' => 'ml',      'location' => 'pantry'],
        ];

        foreach ($items as $data) {
            $ingredient = Ingredient::where('canonical_name', $data['ingredient'])->first();

            if (! $ingredient) {
                continue;
            }

            $inventory->items()->create([
                'ingredient_id' => $ingredient->id,
                'name'          => $ingredient->canonical_name,
                'quantity'      => $data['quantity'],
                'unit'          => $data['unit'],
                'location'      => $data['location'],
            ]);
        }
    }
}
