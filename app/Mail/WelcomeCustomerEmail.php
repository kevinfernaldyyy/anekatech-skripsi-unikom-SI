<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeCustomerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $passwordAsli;

    /**
     * Create a new message instance.
     */
    public function __construct(Customer $customer, $passwordAsli)
    {
        $this->customer = $customer;
        $this->passwordAsli = $passwordAsli;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di Portal Pelanggan DiabloTech!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome_customer', // Kita akan buat file blade ini di langkah 2
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