<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;                  
use Illuminate\Auth\Notifications\ResetPassword;     
use Illuminate\Notifications\Messages\MailMessage;   

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Pemulihan Kata Sandi - DiabloTech POS 🔒')
                ->view('emails.forgot-password', [
                    'url' => $url,
                    'user' => $notifiable,
                ]);
        });
    }
}