<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;

class CartItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, CartItem $cartItem) {
      return $user->id === $cartItem->user_id || $user->type === 'super admin' || $user->type === 'admin';
    }
}
