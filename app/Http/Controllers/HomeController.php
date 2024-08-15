<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller {

  public function index() {
    $products = Product::get();
    $brands = Brand::get();
    $categories = Category::get();

    return Inertia::render('Home', [
      "brands" => $brands,
      "categories" => $categories,
    ]);
  }

}