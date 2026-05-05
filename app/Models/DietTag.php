<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'type', 'color', 'icon', 'video_timestamp'])]
class DietTag extends Model
{
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_tags', 'tag_id', 'recipe_id');
    }
}
