<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller {
  public function processPayment(Request $request, Order $order) {
    $this->authorize('update', $order);

    if ($order->payment && $order->payment->status === 'succeeded') {
      return Redirect::route('order.index')->with('error', 'This order has already been paid.');
    }

    if (!$order->payment) {
      return Redirect::route('order.index')->with('error', 'This order has no address.');
    }

    Stripe::setApiKey(env('VITE_STRIPE_SECRET'));

    try {
      $paymentIntent = PaymentIntent::create([
        'amount' => 1099, // amount in cents (e.g., $10.99)
        'currency' => 'usd',
        'payment_method' => $request->input('payment_method'),
        'confirm' => true,
        'automatic_payment_methods' => [
          'enabled' => true,
          'allow_redirects' => 'never', // Disable redirect-based payment methods
        ],
      ]);

      if ($paymentIntent->status === 'succeeded') {
        $order->payment()->create([
          'order_id' => $request->input('order_id'), // Make sure to pass order_id in the request
          'amount' => $paymentIntent->amount / 100, // Convert from cents to dollars
          'status' => $paymentIntent->status,
          'type' => 'card', // Adjust this based on the payment method used
          'created_by' => auth()->id(), // Assuming the user is authenticated
        ]);
        return Redirect::route('order.index')->with(['success' => 'Payment successful!', 'clientSecret' => $paymentIntent->client_secret]);
      } elseif ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_source_action') {
        return Redirect::to($paymentIntent->next_action->redirect_to_url->url);
      } else {
        return Redirect::route('order.index')->with('error', 'Payment failed!');
      }
    } catch (\Exception $e) {
      return Redirect::route('order.index')->with('error', 'Error: ' . $e->getMessage());
    }

  }

}
