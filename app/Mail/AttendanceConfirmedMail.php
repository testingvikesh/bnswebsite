<?php

namespace App\Mail;

use App\Models\SessionAttendance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $event
     */
    public function __construct(
        public SessionAttendance $attendance,
        public array $event = [],
    ) {}

    public function envelope(): Envelope
    {
        $session = $this->attendance->sessionLabel();

        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'businessnavacharschool1@gmail.com'),
                'Business Navachar School',
            ),
            subject: 'Attendance Confirmed — '.$session.' | Business Navachar School',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.attendance-confirmed',
            with: [
                'attendance' => $this->attendance,
                'event' => $this->event,
            ],
        );
    }
}
