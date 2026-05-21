<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $orderId;

    /**
     * Create a new message instance.
     */
    public function __construct(\App\Models\Order $order)
    {
        $this->orderId = $order->id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $order = \App\Models\Order::findOrFail($this->orderId);
        return new Envelope(
            subject: 'Potwierdzenie zamówienia #' . $order->order_number . ' | Sklep NEVRO',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $order = \App\Models\Order::findOrFail($this->orderId);
        return new Content(
            markdown: 'emails.order-confirmation',
            with: [
                'order' => $order,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
