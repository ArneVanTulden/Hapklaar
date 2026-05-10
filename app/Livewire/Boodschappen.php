<?php

namespace App\Livewire;

use App\Models\ShoppingList;
use Livewire\Attributes\On;
use Livewire\Component;

class Boodschappen extends Component
{
    #[On('shopping-list-updated')]
    public function refresh(): void {}

    public function toggleItem(int $itemId): void
    {
        $item = $this->getList()->items()->findOrFail($itemId);
        $item->update(['is_checked' => !$item->is_checked]);
    }

    public function removeItem(int $itemId): void
    {
        $this->getList()->items()->findOrFail($itemId)->delete();
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
