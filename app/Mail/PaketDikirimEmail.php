<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaketDikirimEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yeay! Paket PC Anda Sedang Dikirim 🚚 [' . $this->transaksi->no_transaksi . ']',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.paket_dikirim',
        );
    }
}