<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaksi;

class PembayaranMasukAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    /**
     * Get the message envelope.
     */
public function envelope(): Envelope
    {
        $jenisPembayaran = !empty($this->transaksi->bukti_pelunasan) ? 'PELUNASAN' : 'DP/AWAL';
        return new Envelope(
            subject: '💰 Pembayaran Masuk (' . $jenisPembayaran . ') - ' . $this->transaksi->no_transaksi,
        );
    }
    public function content(): Content
    {
        return new Content(
            // Kita arahkan ke folder emails/admin/pembayaran_masuk.blade.php
            view: 'emails.pembayaran_masuk',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}