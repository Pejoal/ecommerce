<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy {
  /**
   * Create a new policy instance.
   */
  public function __construct() {
    //
  }
  public function update(User $user, Order $order) {
    return $user->id === $order->created_by || $user->type === 'super admin' || $user->type === 'admin';
  }
}
