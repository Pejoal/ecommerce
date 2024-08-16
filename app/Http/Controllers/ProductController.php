<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
  public function create() {
    // Logic to display the create product form
  }

  public function store(Request $request) {
    // Logic to store a new product
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
