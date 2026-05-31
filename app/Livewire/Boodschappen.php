<?php

namespace App\Livewire;

use App\Models\ShoppingList;
use App\Services\AlbertHeijnService;
use Livewire\Attributes\On;
use Livewire\Component;

class Boodschappen extends Component
{
    public ?string $ahFetchMessage = null;

    #[On('shopping-list-updated')]
    public function refresh(): void {}

    public function toggleItem(int $itemId): void
    {
        $item = $this->getList()->items()->find($itemId);
        if (! $item) return;
        $item->update(['is_checked' => !$item->is_checked]);
    }

    public function removeItem(int $itemId): void
    {
        $this->getList()->items()->where('id', $itemId)->delete();
    }

    public function fetchAhPrices(): void
    {
        $this->ahFetchMessage = null;

        $service = app(AlbertHeijnService::class);
        $items   = $this->getList()->items()->get();

        $queries = $items->mapWithKeys(fn ($item) => [$item->id => $item->name])->all();
        $results = $service->searchProducts($queries);

        $updated = 0;

        foreach ($items as $item) {
            $result = $results[$item->id] ?? null;

            if ($result && $result['price']) {
                $item->update(['price_estimate' => $result['price']]);
                $updated++;
            }
        }

        $this->ahFetchMessage = $updated > 0
            ? "{$updated} PRIJS" . ($updated === 1 ? '' : 'EN') . ' GEVONDEN'
            : 'GEEN PRIJZEN GEVONDEN';
    }

    public function moveCheckedToInventory(): void
    {
        $checkedItems = $this->getList()->items()->where('is_checked', true)->get();
        $inventory    = auth()->user()->inventory()->firstOrCreate([]);

        foreach ($checkedItems as $item) {
            $existing = $inventory->items()
                ->when($item->ingredient_id, fn($q) => $q->where('ingredient_id', $item->ingredient_id))
                ->when(!$item->ingredient_id, fn($q) => $q->where('name', $item->name))
                ->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity ?? 1);
            } else {
                $inventory->items()->create([
                    'ingredient_id' => $item->ingredient_id,
                    'name'          => $item->name,
                    'quantity'      => $item->quantity ?? 1,
                    'unit'          => $item->unit,
                ]);
            }
        }

        $this->getList()->items()->where('is_checked', true)->delete();
    }

    public function removeCheckedItems(): void
    {
        $this->getList()->items()->where('is_checked', true)->delete();
    }

    public function clearAll(): void
    {
        $this->getList()->items()->delete();
    }

    private function getList(): ShoppingList
    {
        return auth()->user()->shoppingLists()->firstOrCreate([]);
    }

    public function render()
    {
        $categoryColors = [
            'bg-[var(--lime)] text-black border-black',
            'bg-brand text-white border-brand',
            'bg-purple-600 text-white border-purple-600',
            'bg-[var(--hot-pink)] text-white border-[var(--hot-pink)]',
            'bg-[var(--yellow)] text-black border-black',
        ];

        $items = $this->getList()->items()->get();

        $sections = $items->groupBy('category')
            ->values()
            ->map(function ($groupItems, $index) use ($categoryColors) {
                return [
                    'label'     => strtoupper($groupItems->first()->category ?? 'OVERIG'),
                    'label_cls' => $categoryColors[$index % count($categoryColors)],
                    'items'     => $groupItems,
                ];
            });

        $totalItems   = $items->count();
        $checkedItems = $items->where('is_checked', true)->count();
        $totalPrice   = $items->sum('price_estimate');

        return view('livewire.boodschappen', compact('sections', 'totalItems', 'checkedItems', 'totalPrice'));
    }
}
