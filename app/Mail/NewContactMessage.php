<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewContactMessage extends Mailable
{
    public function __construct(public ContactMessage $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // Hitting "Reply" in your mail app answers the sender directly.
            replyTo: [new Address($this->contact->email, $this->contact->name)],
            subject: 'New contact message: ' . ($this->contact->subject ?: 'No subject'),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-message');
    }
}
