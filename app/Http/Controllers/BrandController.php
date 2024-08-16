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
}
