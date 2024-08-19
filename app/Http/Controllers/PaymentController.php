<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller {
  public function processPayment(Request $request) {
    Stripe::setApiKey(env('VITE_STRIPE_SECRET'));

    $paymentIntent = PaymentIntent::create([
      'amount' => 1099, // amount in cents
      'currency' => 'usd',
      'payment_method' => $request->input('payment_method'),
      'confirm' => true,
      // 'return_url' => route('payment.handle_return'),
      'automatic_payment_methods' => [
        'enabled' => true,
        'allow_redirects' => 'never', // Disable redirect-based payment methods
      ],
    ]);

    return Redirect::route('order.index')->with('clientSecret', $paymentIntent->client_secret);

  }
}
