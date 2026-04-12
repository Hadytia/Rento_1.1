<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public string $adminName;

    public function __construct(string $otpCode, string $adminName)
    {
        $this->otpCode   = $otpCode;
        $this->adminName = $adminName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Rento Login OTP Code');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp');
    }
}