<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller {

  public function index() {
    $products = Product::with('brand', 'category')->get()->map(function ($product) {
      return [
        "id" => $product->id,
        "title" => $product->title,
        "slug" => $product->slug,
        "quantity" => $product->quantity,
        "description" => $product->description,
        "published" => $product->published,
        "inStock" => $product->inStock,
        "price" => $product->price,
        "brand" => $product->brand ? $product->brand->name : null,
        "category" => $product->category ? $product->category->name : null,
      ];
    });
    $brands = Brand::all();
    $categories = Category::all();

    return Inertia::render('Home', [
      "products" => $products,
      "brands" => $brands,
      "categories" => $categories,
    ]);
  }

}