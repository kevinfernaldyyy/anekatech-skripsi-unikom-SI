<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Variabel untuk menyimpan data pelanggan

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Pendaftaran Berhasil! Selamat Datang di DiabloTech 🔥')
                    ->view('emails.Welcome'); // Mengarah ke desain HTML email
    }
}
