<?php

namespace App\Livewire;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ToggleFavorite extends Component
{
    public int $recipeId;
    public bool $isFavorited = false;

    public function mount(int $recipeId): void
    {
        $this->recipeId = $recipeId;
        $this->isFavorited = Auth::check() && Favorite::where('user_id', Auth::id())
            ->where('recipe_id', $this->recipeId)
            ->exists();
    }

    public function toggle(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $existing = Favorite::where('user_id', Auth::id())
            ->where('recipe_id', $this->recipeId)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->isFavorited = false;
        } else {
            Favorite::create([
                'user_id'   => Auth::id(),
                'recipe_id' => $this->recipeId,
            ]);
            $this->isFavorited = true;
        }
    }

    public function render()
    {
        return view('livewire.toggle-favorite');
    }
}
