<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        Inventory::create(['user_id' => $user->id]);
    }
}
