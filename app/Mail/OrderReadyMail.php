<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReadyMail extends Mailable
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
            subject: 'Hore! Pesanan Anda Siap Diambil - ' . $this->transaksi->no_transaksi,
        );
    }

  public function content(): Content
    {
        return new Content(
            view: 'emails.orders.ready', // <--- UBAH MARKDOWN JADI VIEW
        );
    }
}