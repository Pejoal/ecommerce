<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CurrencyController extends Controller {
  public function index() {
    $currencies = Currency::
      latest('id')
      ->get();

    return Inertia::render('Admin/Currency', [
      "currencies" => $currencies,
    ]);
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'code' => 'required|string|max:255|unique:currencies,code',
      'symbol' => 'required|string|max:255',
    ]);

    auth()->user()->currencies()->create($validated);
  }

  public function edit(Currency $currency) {
    return $currency;
  }

  public function update(Request $request, Currency $currency) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'code' => 'required|string|max:255|unique:currencies,code',
      'symbol' => 'required|string|max:255',
    ]);

    $currency->update($validated);

  }

  public function destroy(Currency $currency) {
    $currency->delete();
  }

  public function massDestroy(Request $request) {
    $ids = $request->input('selectedCurrencies');
    Currency::whereIn('id', $ids)->delete();
  }
}
