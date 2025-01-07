<?php

namespace App\Observers;

use App\Models\Cart;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        Cart::create([
            "user_id" => $user->id
        ]);
    }
}
