<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $favorieten = $user->favoriteRecipes()->with('category')->latest('favorites.created_at')->get();
        $reviews = $user->reviews()->with(['recipe', 'photos'])->latest()->get();
        return view('profiel', compact('favorieten', 'reviews'));
    }
}
