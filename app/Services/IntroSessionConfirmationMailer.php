<?php

namespace App\Services;

use App\Mail\IntroSessionConfirmationMail;
use App\Mail\IntroSessionSequenceMail;
use App\Support\IcsCalendarBuilder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class IntroSessionConfirmationMailer
{
    public const TEMPLATE_WELCOME = 'welcome-confirmation';

    public function __construct(private IcsCalendarBuilder $ics) {}

    /**
     * @param  array<string, mixed>  $inquiry
     * @return array{ok: bool, error: string|null, template_key: string, template_title: string}
     */
    public function send(array $inquiry, ?int $sessionNumber = null, ?string $templateId = null): array
    {
        $email = trim((string) ($inquiry['email'] ?? ''));
        $templateId = trim((string) ($templateId ?: self::TEMPLATE_WELCOME));

        if ($email === '') {
            return [
                'ok' => false,
                'error' => 'Email address is empty.',
                'template_key' => $templateId,
                'template_title' => '',
            ];
        }

        $sessionNumber ??= null;
        $event = null;

        try {
            $sessionNumber ??= bns_intro_session_number_for_count();
            $event = bns_introduction_session((int) $sessionNumber)
                ?? bns_first_introduction_session();
        } catch (\Throwable $e) {
            report($e);
            $sessionNumber = (int) ($sessionNumber ?: config('intro_session_form.default_session_number', 1));
            try {
                $event = bns_first_introduction_session();
            } catch (\Throwable) {
                $event = null;
            }
        }

        $event ??= [
            'title' => 'Introduction Session',
            'date' => '',
            'time' => '',
        ];
        $sessionNumber = (int) ($sessionNumber ?: 1);

        $template = bns_message_email_template($templateId)
            ?? (function_exists('bns_mail_portal_email_template') ? bns_mail_portal_email_template($templateId) : null);
        if ($template === null) {
            return [
                'ok' => false,
                'error' => 'Selected email template was not found.',
                'template_key' => $templateId,
                'template_title' => '',
            ];
        }

        $templateTitle = (string) ($template['title'] ?? $templateId);

        try {
            if (($template['type'] ?? '') === 'welcome' || $templateId === self::TEMPLATE_WELCOME) {
                if (! is_array($event) || empty($event['title'])) {
                    return [
                        'ok' => false,
                        'error' => 'Session event configuration not found.',
                        'template_key' => $templateId,
                        'template_title' => $templateTitle,
                    ];
                }
                $this->sendWelcomeConfirmation($inquiry, $event, $email, (int) $sessionNumber);
            } else {
                $hasContent = trim((string) ($template['whatsapp'] ?? '')) !== ''
                    || trim((string) ($template['rich_html'] ?? '')) !== ''
                    || trim((string) ($template['body_html'] ?? '')) !== '';

                if (! $hasContent) {
                    return [
                        'ok' => false,
                        'error' => 'Selected template has no message content.',
                        'template_key' => $templateId,
                        'template_title' => $templateTitle,
                    ];
                }

                Mail::to($email)->send(new IntroSessionSequenceMail(
                    inquiry: $inquiry,
                    event: is_array($event) ? $event : ['title' => 'Introduction Session'],
                    template: $template,
                ));
            }

            return [
                'ok' => true,
                'error' => null,
                'template_key' => $templateId,
                'template_title' => $templateTitle,
            ];
        } catch (\Throwable $exception) {
            Log::error('Intro session email failed', [
                'email' => $email,
                'template' => $templateId,
                'registration_number' => $inquiry['registration_number'] ?? null,
                'message' => $exception->getMessage(),
            ]);

            return [
                'ok' => false,
                'error' => $exception->getMessage(),
                'template_key' => $templateId,
                'template_title' => $templateTitle,
            ];
        }
    }

    /**
     * @param  array<string, mixed>  $inquiry
     * @param  array<string, mixed>  $event
     */
    private function sendWelcomeConfirmation(array $inquiry, array $event, string $email, int $sessionNumber): void
    {
        $registrationNumber = (string) ($inquiry['registration_number'] ?? 'BNS');
        $fullName = (string) ($inquiry['full_name'] ?? 'Participant');
        $uid = 'bns-intro-session-'.$sessionNumber.'-'.preg_replace('/[^a-zA-Z0-9-]/', '', $registrationNumber).'@businessnavacharschool.com';
        $sessionTitle = (string) ($event['title'] ?? 'Introduction Session 1');
        $reminders = array_map(function ($reminder) use ($sessionTitle) {
            if (is_array($reminder) && ! empty($reminder['description'])) {
                $reminder['description'] = str_replace('Introduction Session 1', $sessionTitle, (string) $reminder['description']);
            }

            return $reminder;
        }, config('intro_session_email.reminders', []));

        $icsContent = $this->ics->build(
            event: $event,
            attendeeEmail: $email,
            attendeeName: $fullName,
            uid: $uid,
            reminders: $reminders,
        );

        $googleCalendarUrl = $this->ics->googleCalendarUrl($event);

        Mail::to($email)->send(new IntroSessionConfirmationMail(
            inquiry: $inquiry,
            event: $event,
            googleCalendarUrl: $googleCalendarUrl,
            icsContent: $icsContent,
        ));
    }
}
