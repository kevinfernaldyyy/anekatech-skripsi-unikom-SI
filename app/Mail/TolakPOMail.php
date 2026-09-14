<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaksi;

class TolakPOMail extends Mailable
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
            subject: 'Pembatalan Pre-Order: ' . $this->transaksi->no_transaksi . ' - DiabloTech',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.po.tolak',
        );
    }
}