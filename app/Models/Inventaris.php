<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'inventaris';

    protected $fillable = [
        'user_id',
        'ingredient_id',
        'naam',
        'hoeveelheid',
        'eenheid',
        'locatie',
    ];

    protected function casts(): array
    {
        return [
            'hoeveelheid' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
