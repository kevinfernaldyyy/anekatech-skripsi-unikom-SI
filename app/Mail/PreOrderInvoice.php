<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PreOrder;
use App\Models\Customer;

class PreOrderInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $preOrder;
    public $customer;

    // Terima data dari Controller
    public function __construct(PreOrder $preOrder, Customer $customer)
    {
        $this->preOrder = $preOrder;
        $this->customer = $customer;
    }

    // Judul Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pre-Order #' . $this->preOrder->no_po . ' - AnekaTech',
        );
    }

    // Tunjuk File View-nya
    public function content(): Content
    {
        return new Content(
            view: 'emails.preorder_invoice', // Kita akan buat file ini di langkah 3
        );
    }
}