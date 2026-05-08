<?php

namespace App\Livewire;

use App\Models\Ingredient;
use App\Models\InventoryItem;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MijnVoorraad extends Component
{
    public string $search = '';
    public array $searchResults = [];
    public ?int $ingredientId = null;
    public string $ingredientName = '';
    public string $ingredientCategory = '';
    public string $qty = '1';
    public string $unit = 'stuks';
    public string $location = 'fridge';

    #[Computed]
    public function items(): array
    {
        $inventory = auth()->user()->inventory()->firstOrCreate([]);

        return $inventory->items()->with('ingredient')->get()->map(fn ($item) => [
            'id'       => $item->id,
            'name'     => strtoupper($item->ingredient->canonical_name),
            'qty'      => rtrim(rtrim((string) $item->quantity, '0'), '.'),
            'unit'     => strtoupper($item->unit),
            'location' => $item->location,
            'category' => strtoupper($item->ingredient->category),
            'catClass' => $this->catClass($item->ingredient->category),
        ])->toArray();
    }

    #[Computed]
    public function fridgeItems(): array
    {
        return array_values(array_filter($this->items, fn ($i) => $i['location'] === 'fridge'));
    }

    #[Computed]
    public function freezerItems(): array
    {
        return array_values(array_filter($this->items, fn ($i) => $i['location'] === 'freezer'));
    }

    #[Computed]
    public function pantryItems(): array
    {
        return array_values(array_filter($this->items, fn ($i) => $i['location'] === 'pantry'));
    }

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
        $this->validate([
            'ingredientId' => 'required|exists:ingredients,id',
            'qty'          => 'required|numeric|min:0',
            'unit'         => 'required|string|max:50',
            'location'     => 'required|in:fridge,freezer,pantry',
        ]);

        $ingredient = Ingredient::findOrFail($this->ingredientId);
        $inventory  = auth()->user()->inventory()->firstOrCreate([]);
        $inventory->items()->create([
            'ingredient_id' => $ingredient->id,
            'name'          => $ingredient->canonical_name,
            'quantity'      => $this->qty,
            'unit'          => $this->unit,
            'location'      => $this->location,
        ]);

        $this->ingredientId       = null;
        $this->ingredientName     = '';
        $this->ingredientCategory = '';
        $this->search             = '';
        $this->searchResults      = [];
        $this->qty                = '1';
        $this->unit               = 'stuks';
        $this->location           = 'fridge';

        $this->dispatch('close-ingredient-modal');
    }

    public function removeItem(int $id): void
    {
        $item = InventoryItem::findOrFail($id);
        abort_if($item->inventory->user_id !== auth()->id(), 403);
        $item->delete();
    }

    public function moveItem(int $id, string $location): void
    {
        if (!in_array($location, ['fridge', 'freezer', 'pantry'])) {
            return;
        }

        $item = InventoryItem::findOrFail($id);
        abort_if($item->inventory->user_id !== auth()->id(), 403);
        $item->update(['location' => $location]);
    }

    private function catClass(string $category): string
    {
        return match ($category) {
            'zuivel'       => 'bg-sky-100 text-sky-700 border-sky-300',
            'groenten'     => 'bg-emerald-100 text-emerald-700 border-emerald-300',
            'vlees'        => 'bg-pink-100 text-pink-700 border-pink-300',
            'vis'          => 'bg-cyan-100 text-cyan-700 border-cyan-300',
            'granen'       => 'bg-amber-100 text-amber-700 border-amber-300',
            'kruiden'      => 'bg-lime-100 text-lime-700 border-lime-300',
            'specerijen'   => 'bg-orange-100 text-orange-700 border-orange-300',
            'peulvruchten' => 'bg-orange-100 text-orange-700 border-orange-300',
            'fruit'        => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'sauzen'       => 'bg-red-100 text-red-700 border-red-300',
            'diepvries'    => 'bg-indigo-100 text-indigo-700 border-indigo-300',
            'blik'         => 'bg-gray-100 text-gray-700 border-gray-300',
            'dranken'      => 'bg-purple-100 text-purple-700 border-purple-300',
            default        => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }

    public function render()
    {
        return view('livewire.mijn-voorraad');
    }
}
