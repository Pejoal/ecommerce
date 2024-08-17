<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartItemController extends Controller {

  public function index() {
    $cartItems = auth()->user()->cartItems()
      ->with([
        'product' => function ($query) {
          $query->with('brand', 'category', 'currency', 'images');
        },
      ])
      ->get();

    return Inertia::render('Cart/Index', [
      'cartItems' => $cartItems,
    ]);
  }

  public function add(Request $request) {
    $request->validate([
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|integer|min:1',
      'status' => 'required|in:in_cart,saved_for_later',
    ]);

    auth()->user()->cartItems()->updateOrCreate(
      [
        'product_id' => $request->product_id,
        'status' => $request->status,
      ],
      [
        'quantity' => \DB::raw('quantity + ' . $request->quantity),
      ]
    );
  }

  public function moveToCart(Request $request, CartItem $cartItem) {
    $request->validate([
      'status' => 'required|in:in_cart,saved_for_later',
    ]);

    $cartItem->status = $request->status;
    $cartItem->save();
  }

  public function update(Request $request, CartItem $cartItem) {
    $request->validate([
      'quantity' => 'required|integer|min:1',
    ]);

    $cartItem->quantity = $request->quantity;
    $cartItem->save();
  }

  public function destroy(CartItem $cartItem) {
    $cartItem->delete();
  }
}
