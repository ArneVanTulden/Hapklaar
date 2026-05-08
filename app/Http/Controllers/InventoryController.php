<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = auth()->user()->inventory()->firstOrCreate([]);

        $items = $inventory->items()->with('ingredient')->get()->map(fn ($item) => [
            'id'       => $item->id,
            'name'     => strtoupper($item->ingredient->canonical_name),
            'qty'      => rtrim(rtrim((string) $item->quantity, '0'), '.'),
            'unit'     => strtoupper($item->unit),
            'location' => $item->location,
            'category' => strtoupper($item->ingredient->category),
            'catClass' => $this->catClass($item->ingredient->category),
        ]);

        return view('mijn-voorraad', compact('items'));
    }

    public function ingredients(Request $request)
    {
        $q = trim($request->input('q', ''));

        return Ingredient::when($q, fn ($query) => $query->where('canonical_name', 'like', "%{$q}%"))
            ->orderBy('canonical_name')
            ->limit(12)
            ->get(['id', 'canonical_name as name', 'category']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity'      => 'required|numeric|min:0',
            'unit'          => 'required|string|max:50',
            'location'      => 'required|in:fridge,freezer,pantry',
        ]);

        $ingredient = Ingredient::find($data['ingredient_id']);
        $inventory  = auth()->user()->inventory()->firstOrCreate([]);
        $item       = $inventory->items()->create([
            'ingredient_id' => $ingredient->id,
            'name'          => $ingredient->canonical_name,
            'quantity'      => $data['quantity'],
            'unit'          => $data['unit'],
            'location'      => $data['location'],
        ]);

        return response()->json([
            'id'       => $item->id,
            'name'     => strtoupper($ingredient->canonical_name),
            'qty'      => rtrim(rtrim((string) $item->quantity, '0'), '.'),
            'unit'     => strtoupper($item->unit),
            'location' => $item->location,
            'category' => strtoupper($ingredient->category),
            'catClass' => $this->catClass($ingredient->category),
        ], 201);
    }

    public function destroy(InventoryItem $item)
    {
        abort_if($item->inventory->user_id !== auth()->id(), 403);
        $item->delete();
        return response()->noContent();
    }

    public function move(Request $request, InventoryItem $item)
    {
        abort_if($item->inventory->user_id !== auth()->id(), 403);
        $item->update($request->validate([
            'location' => 'required|in:fridge,freezer,pantry',
        ]));
        return response()->noContent();
    }

    private function catClass(string $category): string
    {
        return match ($category) {
            'zuivel'       => 'bg-sky-100 text-sky-700 border-sky-300',
            'groenten'     => 'bg-emerald-100 text-emerald-700 border-emerald-300',
            'vlees'        => 'bg-pink-100 text-pink-700 border-pink-300',
            'vis'          => 'bg-cyan-100 text-cyan-700 border-cyan-300',
            'granen'       => 'bg-amber-100 text-amber-700 border-amber-300',
            'kruiden'      => 'bg-lime-100 text-lime-700 border-lime-300',
            'specerijen'   => 'bg-orange-100 text-orange-700 border-orange-300',
            'peulvruchten' => 'bg-orange-100 text-orange-700 border-orange-300',
            'fruit'        => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'sauzen'       => 'bg-red-100 text-red-700 border-red-300',
            'diepvries'    => 'bg-indigo-100 text-indigo-700 border-indigo-300',
            'blik'         => 'bg-gray-100 text-gray-700 border-gray-300',
            'dranken'      => 'bg-purple-100 text-purple-700 border-purple-300',
            default        => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }
}
