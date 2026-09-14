<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HasilCekTidakValid extends Mailable
{
    use Queueable, SerializesModels;

    public $retur;
    public function __construct($retur) {
        $this->retur = $retur;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hasil Cek Tidak Valid',
        );
    }
    public function content(): Content
    {
        return new Content(
            // 👇 INI YANG HARUS DIGANTI (Dari 'view.name' jadi path file aslinya) 👇
            view: 'emails.kustomer.klaim_gugur', 
        );
    }
}
