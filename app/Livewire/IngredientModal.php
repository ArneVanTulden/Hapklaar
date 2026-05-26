<?php

namespace App\Livewire;

use App\Models\Ingredient;
use Livewire\Attributes\On;
use Livewire\Component;

class IngredientModal extends Component
{
    public string $search = '';
    public array $searchResults = [];
    public ?int $ingredientId = null;
    public string $ingredientName = '';
    public string $ingredientCategory = '';
    public string $qty = '1';
    public string $unit = 'stuks';
    public string $location = 'fridge';
    public string $mode = 'inventory';

    public function updatedSearch(): void
    {
        if (!trim($this->search)) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Ingredient::where('canonical_name', 'like', "%{$this->search}%")
            ->orderBy('canonical_name')
            ->limit(12)
            ->get(['id', 'canonical_name as name', 'category'])
            ->toArray();
    }

    public function selectIngredient(int $id): void
    {
        $ingredient = Ingredient::findOrFail($id);
        $this->ingredientId       = $id;
        $this->ingredientName     = strtoupper($ingredient->canonical_name);
        $this->ingredientCategory = strtoupper($ingredient->category);
        $this->search             = '';
        $this->searchResults      = [];
    }

    public function clearIngredient(): void
    {
        $this->ingredientId       = null;
        $this->ingredientName     = '';
        $this->ingredientCategory = '';
    }

    public function addItem(): void
    {
        $rules = [
            'ingredientId' => 'required|exists:ingredients,id',
            'qty'          => 'required|numeric|min:0',
            'unit'         => 'required|string|max:50',
        ];

        if ($this->mode === 'inventory') {
            $rules['location'] = 'required|in:fridge,freezer,pantry';
        }

        $this->validate($rules);

        $ingredient = Ingredient::findOrFail($this->ingredientId);

        if ($this->mode === 'scanner') {
            $this->dispatch('scanner-ingredient-added',
                id:       $ingredient->id,
                name:     $ingredient->canonical_name,
                qty:      $this->qty,
                unit:     $this->unit,
                category: $ingredient->category,
            );
        } elseif ($this->mode === 'shopping') {
            $list = auth()->user()->shoppingLists()->firstOrCreate([]);
            $list->items()->create([
                'ingredient_id' => $ingredient->id,
                'name'          => $ingredient->canonical_name,
                'quantity'      => $this->qty,
                'unit'          => $this->unit,
                'category'      => $ingredient->category,
            ]);
            $this->dispatch('shopping-list-updated');
        } else {
            $inventory = auth()->user()->inventory()->firstOrCreate([]);
            $inventory->items()->create([
                'ingredient_id' => $ingredient->id,
                'name'          => $ingredient->canonical_name,
                'quantity'      => $this->qty,
                'unit'          => $this->unit,
                'location'      => $this->location,
            ]);
            $this->dispatch('inventory-updated');
        }

        $this->ingredientId       = null;
        $this->ingredientName     = '';
        $this->ingredientCategory = '';
        $this->search             = '';
        $this->searchResults      = [];
        $this->qty                = '1';
        $this->unit               = 'stuks';
        $this->location           = 'fridge';
        $this->mode               = 'inventory';

        $this->dispatch('close-ingredient-modal');
    }

    public function render()
    {
        return view('livewire.ingredient-modal');
    }
}
