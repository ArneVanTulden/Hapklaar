<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['list_id', 'ingredient_id', 'name', 'quantity', 'unit', 'price_estimate', 'category', 'is_checked', 'sort_order'])]
class ShoppingListItem extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_checked'     => 'boolean',
            'price_estimate' => 'decimal:2',
        ];
    }

    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class, 'list_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}
