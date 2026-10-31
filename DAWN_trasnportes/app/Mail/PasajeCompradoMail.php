<?php

namespace App\Mail;

use App\Models\Pago;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasajeCompradoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pago $pago,
        public User $comprador,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu pasaje DAWN · '.$this->pago->referencia,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.pasaje-comprado',
            with: [
                'pago' => $this->pago,
                'comprador' => $this->comprador,
            ],
        );
    }
}
