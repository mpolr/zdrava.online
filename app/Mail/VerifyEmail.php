<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $pin;

    public function __construct(string $pin)
    {
        $this->pin = $pin;
    }

    public function build(): VerifyEmail
    {
        return $this
            ->markdown('emails.verify');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Email verification'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.verify',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
