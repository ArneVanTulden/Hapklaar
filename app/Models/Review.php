<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['recipe_id', 'user_id', 'rating', 'title', 'body', 'status'])]
class Review extends Model
{
    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(ReviewPhoto::class)->orderBy('sort_order');
    }
}
