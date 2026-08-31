<?php

namespace App\Mail;

use App\Models\AttendanceQrInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $event
     */
    public function __construct(
        public AttendanceQrInvite $invite,
        public array $event = [],
    ) {}

    public function envelope(): Envelope
    {
        $sessionTitle = (string) ($this->event['title'] ?? ('Session '.$this->invite->session_number));

        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'businessnavacharschool1@gmail.com'),
                'Business Navachar School',
            ),
            subject: 'Attendance QR Invite | '.$sessionTitle.' | BNS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.attendance-invite',
            with: [
                'invite' => $this->invite,
                'event' => $this->event,
                'scanUrl' => $this->invite->scanUrl(),
                'qrUrl' => $this->invite->qrImageUrl(240),
            ],
        );
    }
}
