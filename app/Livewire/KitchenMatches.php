<?php

namespace App\Livewire;

use App\Models\Recipe;
use Livewire\Attributes\On;
use Livewire\Component;

class KitchenMatches extends Component
{
    public string $source = 'scanner';

    public bool $visible = false;

    /** @var array<int,int> ingredient ids feeding the match search */
    public array $scannedIds = [];

    public function mount(string $source = 'scanner'): void
    {
        $this->source = $source;
        $this->visible = $source === 'scanner';
    }

    #[On('scanner-items-updated')]
    public function syncScannedIds(array $ids): void
    {
        if ($this->source !== 'scanner') {
            return;
        }

        $this->scannedIds = array_values(array_unique(array_filter($ids, fn ($v) => $v !== null)));
    }

    #[On('inventory-updated')]
    public function onInventoryUpdated(): void
    {
        if ($this->source === 'inventory' && $this->visible) {
            $this->loadInventoryIds();
        }
    }

    public function showInventoryMatches(): void
    {
        if ($this->source !== 'inventory') {
            return;
        }

        $this->loadInventoryIds();
        $this->visible = true;
    }

    public function hideInventoryMatches(): void
    {
        $this->visible = false;
        $this->scannedIds = [];
    }

    private function loadInventoryIds(): void
    {
        if (! auth()->check()) {
            $this->scannedIds = [];
            return;
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $inventory = $user->inventory()->firstOrCreate([]);

        $this->scannedIds = $inventory->items()
            ->whereNotNull('ingredient_id')
            ->pluck('ingredient_id')
            ->unique()
            ->values()
            ->all();
    }

    public function render()
    {
        $matches = [];

        if ($this->visible && ! empty($this->scannedIds)) {
            $recipes = Recipe::with(['ingredients', 'category'])->get();

            $scanned = array_flip($this->scannedIds);

            $matches = $recipes->map(function (Recipe $recipe) use ($scanned) {
                $ingredients = $recipe->ingredients;
                $total = $ingredients->count();

                if ($total === 0) {
                    return null;
                }

                $missing = [];
                $have = 0;

                foreach ($ingredients as $ing) {
                    if (isset($scanned[$ing->id])) {
                        $have++;
                    } else {
                        $missing[] = strtoupper($ing->canonical_name);
                    }
                }

                if ($have === 0) {
                    return null;
                }

                return [
                    'recipe'  => $recipe,
                    'pct'     => (int) round($have / $total * 100),
                    'missing' => $missing,
                ];
            })->filter()->sortByDesc('pct')->take(3)->values()->all();
        }

        return view('livewire.kitchen-matches', ['matches' => $matches]);
    }
}
