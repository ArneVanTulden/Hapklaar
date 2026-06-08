<?php

namespace App\Livewire;

use App\Models\Recipe;
use Illuminate\View\View;
use Livewire\Component;

class RecipeGrid extends Component
{
    public array $diets = [];
    public int $maxCal = 1500;
    public ?int $maxAfwas = null;
    public string $sort = 'popular';

    public function toggleDiet(string $diet): void
    {
        if (($key = array_search($diet, $this->diets)) !== false) {
            array_splice($this->diets, $key, 1);
        } else {
            $this->diets[] = $diet;
        }
    }

    public function setAfwas(int $value): void
    {
        $this->maxAfwas = $this->maxAfwas === $value ? null : $value;
    }

    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }

    public function resetFilters(): void
    {
        $this->diets = [];
        $this->maxCal = 1500;
        $this->maxAfwas = null;
    }

    public function render(): View
    {
        $query = Recipe::with([
            'dietTags',
            'creator',
            'reviews' => fn($q) => $q->where('status', 'approved')->with('user')->latest(),
        ]);

        foreach ($this->diets as $diet) {
            $query->whereHas('dietTags', fn($q) => $q->where('name', $diet));
        }

        if ($this->maxCal < 1500) {
            $query->where('calories_per_portion', '<=', $this->maxCal);
        }

        if ($this->maxAfwas !== null) {
            $query->where('afwas_score', '<=', $this->maxAfwas);
        }

        match ($this->sort) {
            'snel'   => $query->orderByRaw('prep_time_minutes IS NULL, prep_time_minutes ASC'),
            'gezond' => $query->orderByRaw('calories_per_portion IS NULL, calories_per_portion ASC'),
            default  => $query->orderByDesc('avg_rating'),
        };

        return view('livewire.recipe-grid', ['recipes' => $query->get()]);
    }
}
