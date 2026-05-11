<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ScannerResults extends Component
{
    public array $items = [];

    public ?string $flash = null;

    public function removeItem(int $index): void
    {
        array_splice($this->items, $index, 1);
        $this->items = array_values($this->items);
        $this->flash = null;
    }

    #[On('scanner-ingredient-added')]
    public function addIngredient(int $id, string $name, string $qty, string $unit): void
    {
        $this->items[] = [
            'label'         => strtoupper($name),
            'ingredient_id' => $id,
            'qty'           => $qty,
            'unit'          => $unit,
        ];
        $this->flash = null;
    }

    public function addAllToInventory(): void
    {
        $saveable = array_filter($this->items, fn($i) => $i['ingredient_id'] !== null);

        if (empty($saveable)) {
            return;
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $inventory = $user->inventory()->firstOrCreate([]);

        foreach ($saveable as $item) {
            $inventory->items()->create([
                'ingredient_id' => $item['ingredient_id'],
                'name'          => strtolower($item['label']),
                'quantity'      => $item['qty'],
                'unit'          => $item['unit'],
                'location'      => 'fridge',
            ]);
        }

        $count = count($saveable);
        $this->flash = $count === count($this->items)
            ? "Alle {$count} ingrediënten toegevoegd aan je inventaris!"
            : "{$count} van " . count($this->items) . " ingrediënten toegevoegd (rest niet herkend).";

        $this->dispatch('inventory-updated');
    }

    public function render()
    {
        return view('livewire.scanner-results');
    }
}
