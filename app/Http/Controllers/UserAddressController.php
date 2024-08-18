<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller {

  public function store(Request $request) {
    $validated = $request->validate([
      'type' => 'required|string|max:45',
      'address1' => 'required|string|max:255',
      'address2' => 'nullable|string|max:255',
      'city' => 'required|string|max:255',
      'state' => 'nullable|string|max:45',
      'zipcode' => 'required|string|max:45',
      'country_code' => 'required|string|max:3',
    ]);

    $address = new UserAddress($validated);
    $address->user_id = $request->user()->id;
    $address->save();

    return redirect()->back()->with('success', 'Address added successfully.');
  }

  public function destroy(UserAddress $address) {
    $address->delete();

    return redirect()->back()->with('success', 'Address deleted successfully.');
  }
  public function setAsMainAddress(UserAddress $address) {
    // Unset the current main address if one exists
    auth()->user()->addresses()->where('isMain', 1)->update(['isMain' => 0]);

    // Set the new main address
    $address->isMain = 1;
    $address->save();
  }
}
