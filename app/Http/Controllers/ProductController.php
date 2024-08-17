<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller {

  public function store(Request $request) {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'quantity' => 'required|integer|min:1',
      'description' => 'nullable|string',
      'currency_id' => 'nullable|exists:currencies,id',
      'in_stock' => 'boolean',
      'premium_delivery' => 'boolean',
      'price' => 'required|numeric|min:0',
      'published' => 'boolean',
      'brand_id' => 'nullable|exists:brands,id',
      'category_id' => 'nullable|exists:categories,id',
    ]);

    auth()->user()->products()->create($validated);
  }

  public function edit(Product $product) {
    return $product;
  }

  public function update(Request $request, Product $product) {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'quantity' => 'required|integer|min:1',
      'description' => 'nullable|string',
      'published' => 'boolean',
      'in_stock' => 'boolean',
      'premium_delivery' => 'boolean',
      'price' => 'required|numeric|min:0',
      'currency_id' => 'nullable|exists:currencies,id',
      'brand_id' => 'nullable|exists:brands,id',
      'category_id' => 'nullable|exists:categories,id',
    ]);

    $product->update([ ...$validated, "updated_by" => auth()->id()]);

  }

  public function destroy(Product $product) {
    $product->delete();
    return redirect()->route('admin.dashboard')->with('success', 'Product deleted successfully.');
  }

  public function massDestroy(Request $request) {
    $ids = $request->input('selectedProducts');
    Product::whereIn('id', $ids)->delete();
  }

  public function updateProductPhotos(Request $request) {
    $request->validate([
      'images.*' => 'required|image|mimes:jpg,jpeg,png,bmp|max:2048',
    ]);

    // Assuming you have a product ID passed as a hidden input or in the route
    $productId = $request->id;

    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $image) {
        $path = $image->store('product_images', 'public'); // Store in 'public/product_images' directory

        // Save the image record in the database
        ProductImage::create([
          'product_id' => $productId,
          'image' => $path,
        ]);
      }
    }
  }
}
