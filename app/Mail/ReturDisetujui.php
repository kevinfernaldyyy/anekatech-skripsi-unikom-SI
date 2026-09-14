<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturDisetujui extends Mailable
{
    use Queueable, SerializesModels;

    public $retur;
    public $transaksi; // 👇 1. Daftarkan variabel $transaksi di sini biar dibaca oleh Blade

    public function __construct($retur)
    {
        $this->retur = $retur;
        
        // 👇 2. Ambil data transaksi dari relasi retur
        $this->transaksi = $retur->transaksi; 
    }
    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan Retur Disetujui! 🔄', // Aku tambahin emoji dikit biar notifnya cakep
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kustomer.retur_disetujui', 
        );
    }
}