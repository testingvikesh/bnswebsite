<?php

namespace App\Mail;

use App\Models\MembershipUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public MembershipUpload $upload,
        public string $refundAmount,
        public int $ttlMinutes,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'businessnavacharschool1@gmail.com'),
                'Business Navachar School',
            ),
            subject: 'Refund OTP '.$this->otp.' | Business Navachar School',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refund-otp',
            with: [
                'otp' => $this->otp,
                'upload' => $this->upload,
                'refundAmount' => $this->refundAmount,
                'ttlMinutes' => $this->ttlMinutes,
            ],
        );
    }
}
