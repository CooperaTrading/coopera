<?php

namespace App\Mail;

use App\Models\Proforma;
use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProformaSent extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public QuoteRequest $quoteRequest,
        public Proforma $proforma,
        public string $pdfContent,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Proforma Invoice ' . $this->proforma->proforma_number . ' from Coopera Trading',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.proforma',
            with: [
                'quoteRequest' => $this->quoteRequest,
                'proforma' => $this->proforma,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                $this->proforma->proforma_number . '.pdf',
                ['mime' => 'application/pdf'],
            ),
        ];
    }
}
