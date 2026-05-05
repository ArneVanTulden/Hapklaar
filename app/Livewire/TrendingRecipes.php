<?php

namespace App\Livewire;

use App\Models\Recipe;
use Livewire\Component;

class TrendingRecipes extends Component
{
    public function render()
    {
        return view('livewire.trending-recipes', [
            'recipes' => Recipe::orderByDesc('avg_rating')->with('category')->take(3)->get(),
        ]);
    }
}
