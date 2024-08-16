<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller {
  public function dashboard(Request $request) {
    $products = Product::with('brand', 'category')
      ->select(['id', 'title', 'slug', 'quantity', 'description', 'published', 'in_stock', 'price', 'brand_id', 'category_id'])
      ->get()
      ->map(function ($product) {
        return [
          "id" => $product->id,
          "title" => $product->title,
          "slug" => $product->slug,
          "quantity" => $product->quantity,
          "description" => $product->description,
          "published" => $product->published,
          "in_stock" => $product->in_stock,
          "price" => $product->price,
          "brand" => $product->brand->name ?? null,
          "category" => $product->category->name ?? null,
        ];
      });
      $brands = Brand::all();
      $categories = Category::all();
      
    return Inertia::render('Admin/Dashboard', [
      "products" => $products,
      "brands" => $brands,
      "categories" => $categories,
    ]);
  }

}
