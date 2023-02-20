<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockIsLowMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public string $ingredient)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() : Content | Envelope
    {
        return $this->view('emails.stock-is-low')
            ->subject('Stock is low for ' . $this->ingredient)
            ->with([
                'ingredient' => $this->ingredient,
            ]);
    }
}
