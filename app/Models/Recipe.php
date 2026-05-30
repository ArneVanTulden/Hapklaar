<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'video_url', 'end_video_timestamp', 'image_path', 'badge', 'prep_time_minutes', 'calories_per_portion', 'afwas_score', 'avg_rating', 'review_count', 'category_id', 'created_by', 'transcript'])]
class Recipe extends Model
{
    protected function casts(): array
    {
        return [
            'avg_rating'           => 'decimal:2',
            'calories_per_portion' => 'decimal:2',
            'transcript'           => 'array',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function steps()
    {
        return $this->hasMany(RecipeStep::class)->orderBy('step_number');
    }

    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients', 'recipe_id', 'ingredients_id')
            ->using(RecipeIngredient::class)
            ->withPivot(['quantity', 'unit', 'notes', 'display_order'])
            ->orderByPivot('display_order');
    }

    public function dietTags()
    {
        return $this->belongsToMany(DietTag::class, 'recipe_tags', 'recipe_id', 'tag_id');
    }

    public function nutritionInfo()
    {
        return $this->hasOne(NutritionInfo::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
