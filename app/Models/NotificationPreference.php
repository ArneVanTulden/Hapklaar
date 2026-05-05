<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'is_enabled'])]
class NotificationPreference extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return ['is_enabled' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
