<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmailNotification {
  protected function verificationUrl($notifiable) {
    return URL::temporarySignedRoute(
      'verification.verify',
      Carbon::now()->addMinutes(12000), // Adjust expiration time as needed
      [
        'id' => $notifiable->getKey(),
        'hash' => sha1($notifiable->getEmailForVerification()),
      ]
    );
  }

  public function toMail($notifiable) {
    return (new MailMessage)
      ->subject('Verify Email Address')
      ->line('Please click the button below to verify your email address.')
      ->action('Verify Email Address', $this->verificationUrl($notifiable))
      ->line('If you did not create an account, no further action is required.');
  }
}
