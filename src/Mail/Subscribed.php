<?php

namespace MIBU\Newsletter\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use MIBU\Newsletter\Models\Subscriber;

class Subscribed extends Mailable implements ShouldQueue
{
    // use Queueable, SerializesModels;

    public function __construct(public Subscriber $subscriber)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('newsletter::messages.mail.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'newsletter::emails.subscribed',
        );
    }
}
