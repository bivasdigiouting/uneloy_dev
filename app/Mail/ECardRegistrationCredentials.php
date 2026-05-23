<?php

namespace App\Mail;

use App\Models\ECardRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ECardRegistrationCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public ECardRegistration $registration;

    public string $password;

    public string $loginUrl;

    public function __construct(ECardRegistration $registration, string $password)
    {
        $this->registration = $registration;
        $this->password = $password;
        $this->loginUrl = url('/ecard/login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'UOnly E-Card - Your Login Credentials');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ecard.credentials',
            with: [
                'ecard' => $this->registration,
                'userId' => (string) $this->registration->user_id,
                'plainPassword' => $this->password,
                'loginUrl' => $this->loginUrl,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
