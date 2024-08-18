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

    $mainAddress = auth()->user()->addresses()->where('is_main', 1)->first();
    if (!$mainAddress) {
      return ['error' => 'You should add a main address first.'];
    }

    // Calculate total price
    $totalPrice = $cartItems->sum(function ($item) {
      return $item->quantity * $item->product->price;
    });

    // Create an order
    $order = Order::create([
      'total_price' => $totalPrice,
      'status' => 'pending',
      'user_address_id' => $mainAddress->id,
      'session_id' => session()->getId(),
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
