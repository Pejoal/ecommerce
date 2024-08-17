<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller {
  public function index(Request $request) {
    $query = Product::with('brand', 'category', 'currency');
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

    if ($request->has('minPrice') && !empty($request->minPrice)) {
      $minPrice = $request->minPrice;
      $query->where('price', '>=', $minPrice);
    }

    if ($request->has('maxPrice') && !empty($request->maxPrice)) {
      $maxPrice = $request->maxPrice;
      $query->where('price', '<=', $maxPrice);
    }

    if ($request->has('premium_delivery') && $request->premium_delivery === "true") {
      $query->where('premium_delivery', true);
    }

    if ($request->has('in_stock') && $request->in_stock === "true") {
      $query->where('in_stock', true);
    }

    $perPage = $request->input('perPage', 5); // Default to 5 items per page if not provided
    $paginatedProducts = $query->latest('id')->paginate($perPage);
    $paginatedProducts->getCollection()->transform(function ($product) {
      return [
        "id" => $product->id,
        "title" => $product->title,
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

    return Inertia::render('Home', [
      "products" => $paginatedProducts->toArray(), // Send pagination metadata
      "brands" => Brand::all(),
      "categories" => Category::all(),
    ]);
  }

}
