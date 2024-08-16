<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model {
  use HasFactory, HasSlug;

  protected $guarded = [];

  public function products() {
    return $this->hasMany(Product::class);
  }

  public function getSlugOptions(): SlugOptions {
    return SlugOptions::create()
      ->generateSlugsFrom('name')
      ->saveSlugsTo('slug');
  }
}
