<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaksi;

class PelunasanDikonfirmasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Lunas Dikonfirmasi - ' . $this->transaksi->no_transaksi,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pelunasan_dikonfirmasi', // Kita buat file view-nya di Langkah 2
        );
    }

    public function attachments(): array
    {
        return [];
    }
}