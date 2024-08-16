<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
  public function index(Request $request) {
    $categories = Category::
      latest('id')
      ->get();

    return Inertia::render('Admin/Category', [
      "categories" => $categories,
    ]);
  }
}
