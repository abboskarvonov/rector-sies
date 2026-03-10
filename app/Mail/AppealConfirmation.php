<?php

namespace App\Mail;

use App\Models\Appeal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppealConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Appeal $appeal,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Murojaatingiz qabul qilindi — ' . $this->appeal->tracking_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appeal-confirmation',
        );
    }
}
