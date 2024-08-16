<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {

  public function store(Request $request) {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'slug' => 'required|string|max:255|unique:products',
      'quantity' => 'required|integer|min:1',
      'description' => 'nullable|string',
      'published' => 'boolean',
      'in_stock' => 'boolean',
      'price' => 'required|numeric|min:0',
      'brand_id' => 'nullable|exists:brands,id',
      'category_id' => 'nullable|exists:categories,id',
    ]);

    Product::create($validated);
  }

  public function edit(Product $product) {
    // Logic to display the edit form for a specific product
  }

  public function update(Request $request, Product $product) {
    // Logic to update the product
  }

  public function destroy(Product $product) {
    $product->delete();
    return redirect()->route('admin.dashboard')->with('success', 'Product deleted successfully.');
  }

  public function massDestroy(Request $request) {
    $ids = $request->input('ids');
    Product::whereIn('id', $ids)->delete();
    return redirect()->route('admin.dashboard')->with('success', 'Selected products deleted successfully.');
  }
}
