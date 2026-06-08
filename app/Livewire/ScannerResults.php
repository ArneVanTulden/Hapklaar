<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ScannerResults extends Component
{
    public array $items = [];

    public ?string $flash = null;

    public string $selectedLocation = 'fridge';

    public function mount(): void
    {
        $this->items = session('scanner_items', []);
    }

    public function clearAll(): void
    {
        $this->items = [];
        $this->flash = null;
        session()->forget('scanner_items');
        $this->broadcastItems();
    }

    public function removeItem(int $index): void
    {
        array_splice($this->items, $index, 1);
        $this->items = array_values($this->items);
        $this->flash = null;
        session(['scanner_items' => $this->items]);
        $this->broadcastItems();
    }

    #[On('scanner-ingredient-added')]
    public function addIngredient(?int $id, string $name, string $qty, string $unit, ?string $category = null): void
    {
        $this->items[] = [
            'label'         => strtoupper($name),
            'ingredient_id' => $id,
            'qty'           => $qty,
            'unit'          => $unit,
            'category'      => $category,
        ];
        $this->flash = null;
        session(['scanner_items' => $this->items]);
        $this->broadcastItems();
    }

    private function broadcastItems(): void
    {
        $ids = array_values(array_filter(
            array_map(fn ($i) => $i['ingredient_id'] ?? null, $this->items),
            fn ($v) => $v !== null,
        ));

        $this->dispatch('scanner-items-updated', ids: $ids);
    }

    public function setLocation(string $location): void
    {
        if (in_array($location, ['fridge', 'freezer', 'pantry'])) {
            $this->selectedLocation = $location;
        }
    }

    public function addAllToInventory(): void
    {
        if (! auth()->check()) {
            return;
        }

        $saveable = array_filter($this->items, fn($i) => $i['ingredient_id'] !== null);

        if (empty($saveable)) {
            return;
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $inventory = $user->inventory()->firstOrCreate([]);

        foreach ($saveable as $item) {
            $inventory->items()->firstOrCreate(
                ['ingredient_id' => $item['ingredient_id']],
                [
                    'name'     => strtolower($item['label']),
                    'quantity' => $item['qty'],
                    'unit'     => $item['unit'],
                    'location' => $this->selectedLocation,
                ]
            );
        }

        $count = count($saveable);
        $this->flash = $count === count($this->items)
            ? "Alle {$count} ingrediënten toegevoegd aan je inventaris!"
            : "{$count} van " . count($this->items) . " ingrediënten toegevoegd (rest niet herkend).";

        $this->items = [];
        session()->forget('scanner_items');
        $this->dispatch('inventory-updated');
        $this->broadcastItems();
    }

    public function render()
    {
        return view('livewire.scanner-results');
    }
}
