<?php

namespace App\Mail;

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
        public string $memberName,
        public string $membershipNo,
        public string $registrationNumber,
        public string $mobile,
        public string $refundAmount,
        public int $ttlMinutes,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'info@businessnavacharschool.com'),
                (string) config('mail.from.name', 'Business Navachar School'),
            ),
            replyTo: [
                new Address(
                    (string) config('mail.reply_to.address', config('mail.from.address')),
                    (string) config('mail.reply_to.name', config('mail.from.name', 'Business Navachar School')),
                ),
            ],
            subject: 'Refund OTP '.$this->otp.' | '.$this->memberName.' | BNS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refund-otp',
            with: [
                'otp' => $this->otp,
                'memberName' => $this->memberName,
                'membershipNo' => $this->membershipNo,
                'registrationNumber' => $this->registrationNumber,
                'mobile' => $this->mobile,
                'refundAmount' => $this->refundAmount,
                'ttlMinutes' => $this->ttlMinutes,
            ],
        );
    }
}
