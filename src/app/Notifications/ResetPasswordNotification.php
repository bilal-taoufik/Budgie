<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Reinitialisez votre mot de passe Budgie')
            ->view('mail.reset-password', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'expirationMinutes' => config('auth.passwords.users.expire'),
            ]);
    }
}