<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller {
  public function index() {
    $categories = Category::
      latest('id')
      ->get();

    return Inertia::render('Admin/Category', [
      "categories" => $categories,
    ]);
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'active' => 'boolean',
    ]);

    auth()->user()->categories()->create($validated);
  }

  public function edit(Category $brand) {
    return $brand;
  }

  public function update(Request $request, Category $brand) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'active' => 'boolean',
    ]);

    $brand->update($validated);

  }

  public function destroy(Category $brand) {
    $brand->delete();
  }

  public function massDestroy(Request $request) {
    $ids = $request->input('selectedCategories');
    Category::whereIn('id', $ids)->delete();
  }
}
