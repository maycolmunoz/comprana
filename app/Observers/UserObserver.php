<?php

namespace App\Observers;

use App\Models\Cart;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $carts = collect(range(1, 8))->map(function ($i) use ($user) {
            return [
                'user_id' => $user->id,
                'name' => 'Carrito '.$i,
                'active' => $i === 1,
            ];
        })->toArray();

        Cart::insert($carts);
    }
}
