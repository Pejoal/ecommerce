<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAddress;

class UserAddressPolicy {
  /**
   * Create a new policy instance.
   */
  public function __construct() {
    //
  }

  public function update(User $user, UserAddress $userAddress) {
    return $user->id === $userAddress->user_id;
  }
}
