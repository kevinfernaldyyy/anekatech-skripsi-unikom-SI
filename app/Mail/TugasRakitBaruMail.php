<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TugasRakitBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;
    public $teknisi;

    public function __construct($transaksi, $teknisi)
    {
        $this->transaksi = $transaksi;
        $this->teknisi = $teknisi;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tugas Rakitan Baru: ' . $this->transaksi->no_transaksi,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tugas_rakit_baru', // Kita akan bikin view ini di Langkah 2
        );
    }
}