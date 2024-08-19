<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends Controller {
  public function processPayment(Request $request) {
    dd($request->all());
    Stripe::setApiKey(config('services.stripe.secret'));

    try {
      Charge::create([
        'amount' => 1000, // Amount in cents
        'currency' => 'usd',
        'source' => $request->stripeToken,
        'description' => 'Test Payment',
      ]);

      return redirect()->route('payment.success')->with('success', 'Payment successful!');
    } catch (\Exception $e) {
      return redirect()->route('payment.failure')->with('error', $e->getMessage());
    }
  }
}
