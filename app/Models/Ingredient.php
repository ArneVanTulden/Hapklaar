<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['canonical_name', 'name_en', 'gram_per_unit', 'category', 'kcal_per_100g', 'protein_per_100g', 'carbs_per_100g', 'fat_per_100g', 'fiber_per_100g', 'sugar_per_100g', 'sodium_per_100g', 'potassium_per_100g', 'iron_per_100g', 'vitamin_a_per_100g', 'vitamin_c_per_100g'])]
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
