<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IntroSessionConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $inquiry
     * @param  array<string, mixed>  $event
     */
    public function __construct(
        public array $inquiry,
        public array $event,
        public string $googleCalendarUrl,
        public string $icsContent,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'businessnavacharschool1@gmail.com'),
                'Business Navachar School',
            ),
            subject: str_replace(
                'Introduction Session 1',
                (string) ($this->event['title'] ?? 'Introduction Session 1'),
                (string) config('intro_session_email.subject', 'Introduction Session 1 — Event Details & Calendar Reminder | BNS'),
            ),
        );
    }

    public function content(): Content
    {
        $copy = config('intro_session_email', []);
        $sessionTitle = (string) ($this->event['title'] ?? 'Introduction Session 1');

        foreach (['subject', 'preheader', 'event_heading', 'intro'] as $key) {
            if (! empty($copy[$key]) && is_string($copy[$key])) {
                $copy[$key] = str_replace('Introduction Session 1', $sessionTitle, $copy[$key]);
            }
        }

        if (! empty($copy['reminders']) && is_array($copy['reminders'])) {
            $copy['reminders'] = array_map(function ($reminder) use ($sessionTitle) {
                if (is_array($reminder) && ! empty($reminder['description'])) {
                    $reminder['description'] = str_replace('Introduction Session 1', $sessionTitle, (string) $reminder['description']);
                }

                return $reminder;
            }, $copy['reminders']);
        }

        return new Content(
            view: 'emails.intro-session-confirmation',
            with: [
                'copy' => $copy,
                'inquiry' => $this->inquiry,
                'event' => $this->event,
                'googleCalendarUrl' => $this->googleCalendarUrl,
                'messagesUrl' => route('message.index'),
            ],
        );
    }

    /** @return array<int, Attachment> */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->icsContent, 'introduction-session-'.((int) ($this->event['session_number'] ?? 1)).'.ics')
                ->withMime('text/calendar; charset=UTF-8; method=REQUEST'),
        ];
    }
}
