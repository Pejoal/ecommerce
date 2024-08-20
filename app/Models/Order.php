<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
  use HasFactory;

  protected $guarded = [];

  use HasFactory;

  function items() {
    return $this->hasMany(OrderItem::class);
  }

  public function address() {
    return $this->belongsTo(UserAddress::class, 'user_address_id');
  }

  public function payment() {
    return $this->hasOne(Payment::class);
  }
}
