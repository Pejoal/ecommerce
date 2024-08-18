<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {
  public function store(Request $request) {
    $cartItems = auth()->user()->cartItems()->where('status', 'in_cart')->get();

    if ($cartItems->isEmpty()) {
      return ['error' => 'Your cart is empty.'];
    }

    if (!auth()->user()->default_address_id) {
      return ['error' => 'You should to add a default address first.'];
    }

    // Calculate total price
    $totalPrice = $cartItems->sum(function ($item) {
      return $item->quantity * $item->product->price;
    });

    // Create an order
    $order = Order::create([
      'total_price' => $totalPrice,
      'status' => 'pending',
      'session_id' => session()->getId(),
      'user_address_id' => auth()->user()->default_address_id,
      'created_by' => auth()->id(),
    ]);

    // Add items to the order
    foreach ($cartItems as $item) {
      $order->items()->create([
        'product_id' => $item->product_id,
        'quantity' => $item->quantity,
        'unit_price' => $item->product->price,
      ]);
    }

    // Clear the cart after placing the order
    auth()->user()->cartItems()->where('status', 'in_cart')->delete();

    // Redirect to the payment page or confirmation page
    // return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully.');
  }
}
