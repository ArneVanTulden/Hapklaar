<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function discover()
    {
        return view('ontdekken');
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

    public function removeFromInventory(Request $request, int $id)
    {
        $recipe   = Recipe::with('ingredients')->findOrFail($id);
        $portions = max(1, (int) $request->input('portions', 1));
        /** @var \App\Models\User $user */
        $user      = auth()->user();
        $inventory = $user->inventory()->first();

        if ($inventory) {
            foreach ($recipe->ingredients as $ingredient) {
                if (! $ingredient->pivot->quantity) continue;

                $pivotQty  = (float) $ingredient->pivot->quantity * $portions;
                $pivotUnit = $ingredient->pivot->unit ?? 'g';
                $item      = $inventory->items()->where('ingredient_id', $ingredient->id)->first();

                if (! $item) continue;

                $itemUnit = $item->unit ?? 'g';

                if ($pivotUnit === $itemUnit) {
                    $needed = $pivotQty;
                } else {
                    $gramPerUnit = $ingredient->gram_per_unit ?? null;
                    $pivotGrams  = $this->unitToGrams($pivotQty, $pivotUnit, $gramPerUnit);
                    $itemGrams   = $this->unitToGrams((float) $item->quantity, $itemUnit, $gramPerUnit);

                    if ($pivotGrams === null || $itemGrams === null) {
                        $needed = $pivotQty;
                    } else {
                        $remaining = $itemGrams - $pivotGrams;
                        $remaining <= 0 ? $item->delete() : $item->update(['quantity' => round($remaining, 2), 'unit' => 'g']);
                        continue;
                    }
                }

                $remaining = (float) $item->quantity - $needed;
                $remaining <= 0 ? $item->delete() : $item->update(['quantity' => round($remaining, 2)]);
            }
        }

        return response()->json(['ok' => true]);
    }

    private function unitToGrams(float $quantity, string $unit, ?float $gramPerUnit): ?float
    {
        return match ($unit) {
            'g'                          => $quantity,
            'kg'                         => $quantity * 1000,
            'ml'                         => $quantity,
            'l'                          => $quantity * 1000,
            'eetlepel'                   => $quantity * 15,
            'theelepel'                  => $quantity * 5,
            'stuks', 'blikken', 'zakjes' => $gramPerUnit !== null ? $quantity * $gramPerUnit : null,
            default                      => null,
        };
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
