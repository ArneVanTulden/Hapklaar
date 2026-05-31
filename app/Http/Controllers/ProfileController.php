<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = auth()->user();
        $favorieten = $user->favoriteRecipes()->with('category')->latest('favorites.created_at')->get();
        $reviews = $user->reviews()->with(['recipe', 'photos'])->latest()->get();
        $cooked = $user->cookedRecipes()->with('category')->latest('cooked_at')->get();
        $cookedCount = $cooked->count();
        return view('profiel', compact('favorieten', 'reviews', 'cookedCount', 'cooked'));
    }
}
