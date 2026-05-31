<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function discover(Request $request)
    {
        $query = Recipe::with([
            'dietTags',
            'creator',
            'reviews' => fn($q) => $q->where('status', 'approved')->with('user')->latest(),
        ]);

        foreach ($request->input('diets', []) as $diet) {
            $query->whereHas('dietTags', fn($q) => $q->where('name', $diet));
        }

        $maxCal = $request->integer('max_calories', 1500);
        if ($maxCal < 1500) {
            $query->where('calories_per_portion', '<=', $maxCal);
        }

        if ($request->filled('max_afwas')) {
            $query->where('afwas_score', '<=', $request->integer('max_afwas'));
        }

        $sort = $request->input('sort', 'popular');
        match ($sort) {
            'snel'     => $query->orderByRaw('prep_time_minutes IS NULL, prep_time_minutes ASC'),
            'goedkoop' => $query->orderByRaw('calories_per_portion IS NULL, calories_per_portion ASC'),
            default    => $query->orderByDesc('avg_rating'),
        };

        $recipes = $query->get();
        return view('ontdekken', compact('recipes', 'sort'));
    }

    public function show(int $id)
    {
        $recipe = Recipe::with(['ingredients', 'nutritionInfo'])->findOrFail($id);
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        $alreadyCooked = $user && $user->cookedRecipes()->where('recipe_id', $id)->exists();
        return view('recept', compact('recipe', 'alreadyCooked'));
    }

    public function random()
    {
        $recipe = Recipe::inRandomOrder()->firstOrFail();
        return redirect()->route('recept', $recipe->id);
    }

    public function addToShoppingList(Request $request, int $id)
    {
        $recipe   = Recipe::with('ingredients')->findOrFail($id);
        $portions = max(1, (int) $request->input('portions', 1));
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $list = $user->shoppingLists()->firstOrCreate([]);

        foreach ($recipe->ingredients as $ingredient) {
            $qty = $ingredient->pivot->quantity ? round($ingredient->pivot->quantity * $portions, 2) : null;

            $list->items()->create([
                'ingredient_id' => $ingredient->id,
                'name'          => $ingredient->canonical_name,
                'quantity'      => $qty,
                'unit'          => $ingredient->pivot->unit,
                'category'      => $ingredient->category,
                'sort_order'    => $list->items()->max('sort_order') + 1,
            ]);
        }

        return back()->with('boodschappen_success', 'Ingrediënten toegevoegd aan boodschappenlijst.');
    }

    public function markAsCooked(int $id)
    {
        $recipe = Recipe::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->cookedRecipes()->syncWithoutDetaching([$recipe->id => ['cooked_at' => now()]]);
        return request()->wantsJson()
            ? response()->json(['ok' => true])
            : back()->with('gemaakt_success', true);
    }
}
