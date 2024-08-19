<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class OrderController extends Controller {

  public function index(Request $request) {
    $orders = auth()->user()->orders()->with(['items.product', 'address'])->get()->map(function ($order) {
      $order->total_price = $order->items->sum(function ($item) {
        return $item->product->price * $item->quantity;
      });
      return $order;
    });

    $addresses = auth()->user()->addresses;

    return Inertia::render('Order/Index', [
      'orders' => $orders,
      'addresses' => $addresses,
    ]);
  }
  public function store(Request $request) {
    $cartItems = auth()->user()->cartItems()->where('status', 'in_cart')->get();

    if ($cartItems->isEmpty()) {
      return Redirect::route('cart.index')->with('error', 'Your cart is empty.');
    }

    $mainAddress = auth()->user()->addresses()->where('is_main', 1)->first();
    if (!$mainAddress) {
      return Redirect::route('cart.index')->with('error', 'You should add a main address first.');
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

    return Inertia::location(route('order.index'));
  }

  public function updateAddress(Request $request, Order $order) {
    $order->user_address_id = $request->addressId;
    $order->save();
  }
}
