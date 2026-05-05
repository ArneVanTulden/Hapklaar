<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['recipe_id', 'step_number', 'description', 'video_url', 'video_timestamp'])]
class RecipeStep extends Model
{
    public $timestamps = false;

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
