<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Fillable(['recipe_id', 'ingredients_id', 'quantity', 'unit', 'notes', 'display_order'])]
class RecipeIngredient extends Pivot
{
    public $timestamps = false;
    protected $table = 'recipe_ingredients';
    public $incrementing = true;

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredients_id');
    }
}
