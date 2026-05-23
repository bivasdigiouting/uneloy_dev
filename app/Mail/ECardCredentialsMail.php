<?php

namespace App\Mail;

use App\Models\ECardRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ECardCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public ECardRegistration $ecard;

    public string $userId;

    public string $plainPassword;

    public string $loginUrl;

    public function __construct(ECardRegistration $ecard, string $userId, string $plainPassword, string $loginUrl)
    {
        $this->ecard = $ecard;
        $this->userId = $userId;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Your E-Card Portal Access Credentials')
            ->view('emails.ecard.credentials')
            ->with([
                'ecard' => $this->ecard,
                'userId' => $this->userId,
                'plainPassword' => $this->plainPassword,
                'loginUrl' => $this->loginUrl,
            ]);
    }
}
