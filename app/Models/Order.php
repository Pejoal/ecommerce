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
}
