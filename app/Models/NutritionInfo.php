<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['recipe_id', 'calories', 'protein', 'carbs', 'fat', 'sugar', 'vitamin_a', 'vitamin_c', 'iron', 'sodium', 'potassium', 'fiber'])]
class NutritionInfo extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'calories'  => 'decimal:2',
            'protein'   => 'decimal:2',
            'carbs'     => 'decimal:2',
            'fat'       => 'decimal:2',
            'sugar'     => 'decimal:2',
            'vitamin_a' => 'decimal:2',
            'vitamin_c' => 'decimal:2',
            'iron'      => 'decimal:2',
            'sodium'    => 'decimal:2',
            'potassium' => 'decimal:2',
            'fiber'     => 'decimal:2',
        ];
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
