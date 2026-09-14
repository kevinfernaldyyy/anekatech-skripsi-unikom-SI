<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;
    public $jenis; // 👈 Tambahin ini

    // 👈 Tambahin $jenis di dalam kurung construct
    public function __construct($transaksi, $jenis = 'awal') 
    {
        $this->transaksi = $transaksi;
        $this->jenis = $jenis;
    }

    public function build()
    {
        // Ubah subjek email biar ngikutin jenis penolakannya
        $subject = $this->jenis == 'pelunasan' ? 'Verifikasi Pelunasan Gagal' : 'Verifikasi Pembayaran Gagal';

        return $this->subject($subject)
                    ->view('emails.payment_rejected'); // Sesuaikan nama view kamu
    }
}