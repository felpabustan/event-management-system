<?php

namespace App\Mail;

use App\Models\Registration;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendeeConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Registration $registration,
        public Event $event
    ) {
        // Generate QR code token if not exists
        if (!$this->registration->qr_code_token) {
            $this->registration->generateQrCodeToken();
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Registration Confirmation - ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Generate QR code
        $qrCode = QrCode::format('svg')
            ->size(200)
            ->generate($this->registration->getQrCodeData());

        // QR Token for manual check-in
        $token = $this->registration->qr_code_token;

        return new Content(
            markdown: 'emails.attendee-confirmation',
            with: [
                'registration' => $this->registration,
                'event' => $this->event,
                'qrCode' => $qrCode,
                'token' => $token
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
