<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['session_id', 'ingredients_id', 'name'])]
class ScannedDetected extends Model
{
    public $timestamps = false;

    public function session()
    {
        return $this->belongsTo(ScannerSession::class, 'session_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredients_id');
    }
}
