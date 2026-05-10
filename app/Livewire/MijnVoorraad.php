<?php

namespace App\Livewire;

use App\Models\InventoryItem;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class MijnVoorraad extends Component
{
    #[On('inventory-updated')]
    public function onInventoryUpdated(): void {}

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

    public function updateQuantity(int $id, int $delta): void
    {
        $item = InventoryItem::findOrFail($id);
        abort_if($item->inventory->user_id !== auth()->id(), 403);

        $newQty = $item->quantity + $delta;
        if ($newQty <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $newQty]);
        }
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
            'zuivel'       => 'bg-sky-200 text-sky-900',
            'groenten'     => 'bg-lime-300 text-black',
            'vlees'        => 'bg-pink-500 text-white',
            'vis'          => 'bg-cyan-300 text-cyan-900',
            'granen'       => 'bg-amber-300 text-black',
            'kruiden'      => 'bg-lime-300 text-black',
            'specerijen'   => 'bg-orange-300 text-black',
            'peulvruchten' => 'bg-amber-300 text-black',
            'fruit'        => 'bg-yellow-300 text-black',
            'sauzen'       => 'bg-red-400 text-white',
            'diepvries'    => 'bg-slate-800 text-white',
            'blik'         => 'bg-gray-300 text-black',
            'dranken'      => 'bg-purple-400 text-white',
            default        => 'bg-gray-300 text-black',
        };
    }

    public function render()
    {
        return view('livewire.mijn-voorraad', [
            'items'        => $this->items,
            'fridgeItems'  => $this->fridgeItems,
            'freezerItems' => $this->freezerItems,
            'pantryItems'  => $this->pantryItems,
        ]);
    }
}
