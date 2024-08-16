<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller {
  public function index(Request $request) {
    $products = Product::with('brand', 'category', 'currency')
      ->latest('id')
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
          "premium_delivery" => $product->premium_delivery,
          "price" => $product->price,
          "currency" => $product->currency->symbol ?? null,
          "brand" => $product->brand->name ?? null,
          "category" => $product->category->name ?? null,
        ];
      });
    $brands = Brand::all();
    $categories = Category::all();
    $currencies = Currency::all();

    return Inertia::render('Admin/Dashboard', [
      "products" => $products,
      "brands" => $brands,
      "categories" => $categories,
      "currencies" => $currencies,
    ]);
  }

}
