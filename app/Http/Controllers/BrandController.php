<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller {
  public function index() {
    $brands = Brand::
      latest('id')
      ->get();

    return Inertia::render('Admin/Brand', [
      "brands" => $brands,
    ]);
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'active' => 'boolean',
    ]);

    auth()->user()->brands()->create($validated);
  }

  public function edit(Brand $brand) {
    return $brand;
  }

  public function update(Request $request, Brand $brand) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'active' => 'boolean',
    ]);

    $brand->update($validated);

  }

  public function destroy(Brand $brand) {
    $brand->delete();
  }

  public function massDestroy(Request $request) {
    $ids = $request->input('selectedBrands');
    Brand::whereIn('id', $ids)->delete();
  }
}
