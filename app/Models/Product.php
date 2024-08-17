<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model {
  use HasSlug;
  use HasFactory;
  protected $guarded = [];

  public function getSlugOptions(): SlugOptions {
    return SlugOptions::create()
      ->generateSlugsFrom('title')
      ->saveSlugsTo('slug');
  }

  public function creator() {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function images() {
    return $this->hasMany(ProductImage::class);
  }

  public function category() {
    return $this->belongsTo(Category::class);
  }

  public function brand() {
    return $this->belongsTo(Brand::class);
  }
  public function cartItems() {
    return $this->hasMany(CartItem::class);
  }

  public function currency() {
    return $this->belongsTo(Currency::class);
  }

}
