<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller {
  /**
   * Display the password reset view.
   */
  public function create(Request $request): Response {
    return Inertia::render('Auth/ResetPassword', [
      'email' => $request->email,
      'token' => $request->route('token'),
    ]);
  }

  /**
   * Handle an incoming new password request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse {
    $input_type = filter_var($request->input('input_type'), FILTER_VALIDATE_EMAIL) ? "email" : "username";
    $request->merge([$input_type => $request->input('input_type')]);
    $request->validate([
      'token' => 'required',
      'username' => ['required_without:email', 'string', 'exists:users,username'],
      'email' => ['required_without:username', 'email', 'exists:users,email'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // Here we will attempt to reset the user's password. If it is successful we
    // will update the password on an actual user model and persist it to the
    // database. Otherwise we will parse the error and return the response.
    $status = Password::reset(
      $request->only($input_type, 'password', 'password_confirmation', 'token'),
      function ($user) use ($request) {
        $user->forceFill([
          'password' => Hash::make($request->password),
          'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
      }
    );

    // If the password was successfully reset, we will redirect the user back to
    // the application's home authenticated view. If there is an error we can
    // redirect them back to where they came from with their error message.
    if ($status == Password::PASSWORD_RESET) {
      return redirect()->route('login')->with('status', __($status));
    }

    throw ValidationException::withMessages([
      $input_type => [trans($status)],
    ]);
  }
}
