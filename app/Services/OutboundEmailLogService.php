<?php

namespace App\Services;

use App\Models\OutboundEmailLog;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Throwable;

class OutboundEmailLogService
{
    private ?string $currentMailer = null;

    public function rememberMailer(?string $mailer): void
    {
        $this->currentMailer = $mailer ?: null;
    }

    public function currentMailer(): ?string
    {
        return $this->currentMailer;
    }

    public function recordFromSymfonyMessage(Email $message, string $status = OutboundEmailLog::STATUS_SENT, ?string $error = null): void
    {
        if (! $this->ready()) {
            return;
        }

        $headers = $message->getHeaders();
        $process = $this->headerValue($headers, 'X-BNS-Process')
            ?: $this->processFromSubject((string) $message->getSubject());
        $mailable = $this->headerValue($headers, 'X-BNS-Mailable');
        $mailer = $this->headerValue($headers, 'X-BNS-Mailer') ?: $this->currentMailer;

        $this->write([
            'process' => $process ?: OutboundEmailLog::PROCESS_OTHER,
            'mailable' => $mailable ? class_basename($mailable) : null,
            'to_email' => $this->formatAddresses($message->getTo()),
            'cc_email' => $this->formatAddresses($message->getCc()) ?: null,
            'from_email' => $this->formatAddresses($message->getFrom()) ?: config('mail.from.address'),
            'subject' => $this->limit((string) $message->getSubject(), 500),
            'body_html' => $this->body((string) ($message->getHtmlBody() ?? '')),
            'body_text' => $this->body((string) ($message->getTextBody() ?? '')),
            'status' => $status,
            'error_message' => $error,
            'mailer' => $mailer,
        ]);
    }

    public function recordFailed(string|array $to, Mailable $mailable, string $error): void
    {
        if (! $this->ready()) {
            return;
        }

        $subject = '';
        try {
            $subject = (string) ($mailable->envelope()->subject ?? '');
        } catch (Throwable) {
            $subject = (string) ($mailable->subject ?? '');
        }

        $this->write([
            'process' => OutboundEmailLog::processKeyForMailable($mailable),
            'mailable' => class_basename($mailable),
            'to_email' => implode(', ', array_map('strval', is_array($to) ? $to : [$to])),
            'from_email' => config('mail.from.address'),
            'subject' => $this->limit($subject, 500),
            'status' => OutboundEmailLog::STATUS_FAILED,
            'error_message' => $this->limit($error, 5000),
            'mailer' => $this->currentMailer,
        ]);
    }

    public function processFromViewData(array $data, ?string $subject = null): string
    {
        if (array_key_exists('otp', $data)) {
            return OutboundEmailLog::PROCESS_REFUND;
        }
        if (array_key_exists('payment', $data)) {
            return OutboundEmailLog::PROCESS_PAYMENT;
        }
        if (array_key_exists('invite', $data)) {
            return OutboundEmailLog::PROCESS_ATTENDANCE_INVITE;
        }
        if (array_key_exists('attendance', $data)) {
            return OutboundEmailLog::PROCESS_ATTENDANCE;
        }
        if (array_key_exists('template', $data)) {
            return OutboundEmailLog::PROCESS_SESSION_MAIL;
        }
        if (array_key_exists('googleCalendarUrl', $data) || array_key_exists('icsContent', $data)) {
            return OutboundEmailLog::PROCESS_INTRO_SESSION;
        }

        return $this->processFromSubject((string) $subject);
    }

    public function processFromSubject(string $subject): string
    {
        $lower = strtolower($subject);

        if (str_contains($lower, 'payment')) {
            return OutboundEmailLog::PROCESS_PAYMENT;
        }
        if (str_contains($lower, 'attendance qr') || str_contains($lower, 'attendance invite')) {
            return OutboundEmailLog::PROCESS_ATTENDANCE_INVITE;
        }
        if (str_contains($lower, 'attendance')) {
            return OutboundEmailLog::PROCESS_ATTENDANCE;
        }
        if (str_contains($lower, 'refund')) {
            return OutboundEmailLog::PROCESS_REFUND;
        }
        if (str_contains($lower, 'reset password') || str_contains($lower, 'password reset')) {
            return OutboundEmailLog::PROCESS_PASSWORD_RESET;
        }
        if (str_contains($lower, 'introduction session') || str_contains($lower, 'calendar reminder')) {
            return OutboundEmailLog::PROCESS_INTRO_SESSION;
        }

        return OutboundEmailLog::PROCESS_OTHER;
    }

    /** @param  array<string, mixed>  $attributes */
    private function write(array $attributes): void
    {
        try {
            $user = Auth::user();

            OutboundEmailLog::query()->create([
                ...$attributes,
                'sent_by' => $user?->id,
                'sent_by_name' => $user?->name ?: 'System',
                'sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function ready(): bool
    {
        try {
            return Schema::hasTable('outbound_email_logs');
        } catch (Throwable) {
            return false;
        }
    }

    /** @param  array<int, Address>|null  $addresses */
    private function formatAddresses(?array $addresses): string
    {
        if (! $addresses) {
            return '';
        }

        return collect($addresses)
            ->map(function ($address) {
                if ($address instanceof Address) {
                    return $address->getAddress();
                }

                return (string) $address;
            })
            ->filter()
            ->implode(', ');
    }

    private function headerValue(object $headers, string $name): ?string
    {
        try {
            if (! method_exists($headers, 'has') || ! $headers->has($name)) {
                return null;
            }

            $header = $headers->get($name);
            $value = method_exists($header, 'getBodyAsString')
                ? $header->getBodyAsString()
                : (string) $header;

            $value = trim($value);

            return $value !== '' ? $value : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function body(string $value): ?string
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        return $this->limit($value, 200000);
    }

    private function limit(string $value, int $max): string
    {
        if (strlen($value) <= $max) {
            return $value;
        }

        return substr($value, 0, $max);
    }
}
