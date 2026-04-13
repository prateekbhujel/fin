<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New '.$this->transaction->type.' transaction recorded',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transactions.created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
