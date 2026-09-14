<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KomplainMasukAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $transaksi;
    public $alasan;

    public function __construct($transaksi, $alasan)
    {
        $this->transaksi = $transaksi;
        $this->alasan = $alasan;
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ KOMPLAIN BARU MASUK!',
        );
    }

   public function content(): Content
    {
        return new Content(
            view: 'emails.admin.komplain_masuk', 
        );
    }
}


