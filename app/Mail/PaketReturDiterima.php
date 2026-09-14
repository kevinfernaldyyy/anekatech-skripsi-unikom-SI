<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaketReturDiterima extends Mailable
{
    use Queueable, SerializesModels;

    public $retur;

    public function __construct($retur)
    {
        $this->retur = $retur;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🛠️ Paket Retur Tiba & Sedang Dicek',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kustomer.paket_diterima', 
        );
    }
}