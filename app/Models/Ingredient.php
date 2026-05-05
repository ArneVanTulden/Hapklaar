<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Ingredient extends Model
{
    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class, 'ingredients_id');
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients', 'ingredients_id', 'recipe_id')
            ->using(RecipeIngredient::class)
            ->withPivot(['quantity', 'unit', 'notes', 'display_order']);
    }

    public function shoppingListItems()
    {
        return $this->hasMany(ShoppingListItem::class, 'ingredient_id');
    }

    public function scannedDetected()
    {
        return $this->hasMany(ScannedDetected::class, 'ingredients_id');
    }
}
