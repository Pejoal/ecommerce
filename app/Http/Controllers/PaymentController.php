<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller {
  public function createPaymentIntent(Request $request) {
    Stripe::setApiKey(config('services.stripe.secret'));

    $amount = $request->input('amount'); // amount should be passed from frontend

    $paymentIntent = PaymentIntent::create([
      'amount' => $amount * 100, // convert amount to cents
      'currency' => 'usd',
      'payment_method_types' => ['card'],
    ]);

    return response()->json([
      'clientSecret' => $paymentIntent->client_secret,
    ]);
  }
}
