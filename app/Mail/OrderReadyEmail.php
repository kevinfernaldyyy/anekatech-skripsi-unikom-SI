<?php

namespace App\Mail;

use App\Models\Transaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReadyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function envelope(): Envelope
    {
        // Subject emailnya menyesuaikan apakah diambil atau dikirim
        $statusTeks = $this->transaksi->metode_pengiriman == 'pickup' ? 'Siap Diambil' : 'Sedang Diproses untuk Pengiriman';
        
        return new Envelope(
            subject: 'Hore! Pesanan ' . $this->transaksi->no_transaksi . ' Anda ' . $statusTeks,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_ready',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}