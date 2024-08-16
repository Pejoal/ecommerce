<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller {
  public function index(Request $request) {
    $query = Product::with('brand', 'category', 'currency')
      ->select(['id', 'title', 'slug', 'quantity', 'description', 'published', 'in_stock', 'price', 'currency_id', 'brand_id', 'category_id']);

    if ($request->has('search') && !empty($request->search)) {
      $search = $request->search;
      $query->where('title', 'like', "%{$search}%");
    }

    if ($request->has('selectedBrands') && !empty($request->selectedBrands)) {
      $query->whereIn('brand_id', $request->selectedBrands);
    }

    if ($request->has('selectedCategories') && !empty($request->selectedCategories)) {
      $query->whereIn('category_id', $request->selectedCategories);
    }

    // if ($request->has('isPremiumDeliveryChecked') && $request->isPremiumDeliveryChecked) {
    //   $query->where('premium_delivery', true);
    // }

    $products = $query->latest('id')->paginate(5)->map(function ($product) {
      return [
        "id" => $product->id,
        "title" => $product->title,
        "slug" => $product->slug,
        "quantity" => $product->quantity,
        "description" => $product->description,
        "published" => $product->published,
        "in_stock" => $product->in_stock,
        "price" => $product->price,
        "currency" => $product->currency->symbol ?? null,
        "brand" => $product->brand->name ?? null,
        "category" => $product->category->name ?? null,
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
