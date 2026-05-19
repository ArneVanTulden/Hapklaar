<?php

namespace App\Livewire;

use App\Models\Recipe;
use Livewire\Attributes\On;
use Livewire\Component;

class KitchenMatches extends Component
{
    /** @var array<int,int> ingredient ids the user has scanned */
    public array $scannedIds = [];

    #[On('scanner-items-updated')]
    public function syncScannedIds(array $ids): void
    {
        $this->scannedIds = array_values(array_unique(array_filter($ids, fn ($v) => $v !== null)));
    }

    public function render()
    {
        $matches = [];

        if (! empty($this->scannedIds)) {
            $recipes = Recipe::with(['ingredients', 'category'])->get();

            $scanned = array_flip($this->scannedIds);

            $scored = $recipes->map(function (Recipe $recipe) use ($scanned) {
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

            $matches = $scored;
        }

        return view('livewire.kitchen-matches', ['matches' => $matches]);
    }
}
