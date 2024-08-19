<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller {

  public function edit(UserAddress $address) {
    return $address;
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'type' => 'required|string|max:45',
      'address1' => 'required|string|max:255',
      'address2' => 'nullable|string|max:255',
      'city' => 'required|string|max:255',
      'state' => 'nullable|string|max:45',
      'zipcode' => 'required|string|max:45',
      'country_code' => 'required|string|max:3',
      'is_main' => 'boolean',
    ]);

    if ($validated['is_main']) {
      auth()->user()->addresses()->where('is_main', 1)->update(['is_main' => 0]);
    }

    auth()->user()->addresses()->create($validated);
  }

  public function update(Request $request, UserAddress $address) {
    $validated = $request->validate([
      'type' => 'required|string|max:45',
      'address1' => 'required|string|max:255',
      'address2' => 'nullable|string|max:255',
      'city' => 'required|string|max:255',
      'state' => 'nullable|string|max:45',
      'zipcode' => 'required|string|max:45',
      'country_code' => 'required|string|max:3',
      'is_main' => 'boolean',
    ]);

    if ($validated['is_main']) {
      auth()->user()->addresses()->where('is_main', 1)->update(['is_main' => 0]);
    }

    $address->update($validated);
  }

  public function destroy(UserAddress $address) {
    // Note: if there is an order associated with this address, it will not be deleted
    $orders = Order::where('user_address_id', $address->id)->get();
    foreach ($orders as $order) {
      // Update or handle orders as needed
      $order->update(['user_address_id' => null]); // or set to a different address
    }

    $address->delete();
  }
  public function setAsMainAddress(UserAddress $address, Request $request) {
    // Unset the current main address if one exists
    auth()->user()->addresses()->update(['is_main' => 0]);

    // Set the new main address
    $address->is_main = $request->is_main;
    $address->save();
  }

  public function massDestroy(Request $request) {
    $ids = $request->input('selectedAddresses');
    UserAddress::whereIn('id', $ids)->delete();
  }
}
