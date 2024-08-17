<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartItemController extends Controller {

  public function index() {
    $cartItems = auth()->user()->cartItems()
      ->where('status', 'in_cart')
      ->with([
        'product' => function ($query) {
          $query->with('brand', 'category', 'currency', 'images');
        },
      ])
      ->get();

    $cartItemsTotalPrice = $cartItems->sum(function ($cartItem) {
      return $cartItem->product->price * $cartItem->quantity;
    });

    $savedForLaterItems = auth()->user()->cartItems()
      ->where('status', 'saved_for_later')
      ->with([
        'product' => function ($query) {
          $query->with('brand', 'category', 'currency', 'images');
        },
      ])
      ->get();

      
    $savedForLaterItemsTotalPrice = $savedForLaterItems->sum(function ($cartItem) {
      return $cartItem->product->price * $cartItem->quantity;
    });

    return Inertia::render('Cart/Index', [
      'cartItems' => $cartItems,
      'savedForLaterItems' => $savedForLaterItems,
      'cartItemsTotalPrice' => $cartItemsTotalPrice,
      'savedForLaterItemsTotalPrice' => $savedForLaterItemsTotalPrice,
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

  public function moveToCart(CartItem $cartItem) {
    $cartItem->status = "in_cart";
    $cartItem->save();
  }

  public function saveForLater(CartItem $cartItem) {
    $cartItem->status = "saved_for_later";
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
