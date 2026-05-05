<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['review_id', 'photo_url', 'sort_order'])]
class ReviewPhoto extends Model
{
    public $timestamps = false;

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
