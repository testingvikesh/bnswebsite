<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IntroSessionSequenceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $inquiry
     * @param  array<string, mixed>  $event
     * @param  array<string, mixed>  $template
     */
    public function __construct(
        public array $inquiry,
        public array $event,
        public array $template,
    ) {}

    public function envelope(): Envelope
    {
        $templateTitle = (string) ($this->template['title'] ?? 'BNS Message');
        $isMailPortal = ($this->template['type'] ?? '') === 'mail_portal'
            || ($this->template['layout'] ?? '') === 'coach';

        if ($isMailPortal) {
            $subject = $templateTitle.' | Business Coach | BNS';
        } else {
            $sessionTitle = (string) ($this->event['title'] ?? 'Introduction Session');
            $subject = $templateTitle.' | '.$sessionTitle.' | BNS';
        }

        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'businessnavacharschool1@gmail.com'),
                'Business Navachar School',
            ),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $isMailPortal = ($this->template['type'] ?? '') === 'mail_portal'
            || ($this->template['layout'] ?? '') === 'coach';

        return new Content(
            view: 'emails.intro-session-sequence',
            with: [
                'inquiry' => $this->inquiry,
                'event' => $this->event,
                'template' => $this->template,
                'messagesUrl' => $isMailPortal
                    ? 'https://businessnavacharschool.com'
                    : route('message.index'),
                'isMailPortal' => $isMailPortal,
            ],
        );
    }
}
