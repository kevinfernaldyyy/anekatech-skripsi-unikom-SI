<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaksi;

class PesananBaruAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;
    public $isPO;

    public function __construct(Transaksi $transaksi) 
    {
        $this->transaksi = $transaksi;
        $this->isPO = $transaksi->details->whereNull('barang_id')->count() > 0;
    }

    public function envelope(): Envelope
    {
        $jenis = $this->isPO ? '[PO BARU]' : '[PESANAN BARU]';
        return new Envelope(
            subject: $jenis . ' ' . $this->transaksi->no_transaksi . ' - DiabloTech',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pesanan_baru',
        );
    }
}