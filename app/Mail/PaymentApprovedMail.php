<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaksi;

class PaymentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    // Masukkan data transaksi ke dalam email
    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function build()
    {
        return $this->subject('Pembayaran Diterima - Pesanan ' . $this->transaksi->no_transaksi . ' Diproses')
                    ->view('emails.payment_approved');
    }
}