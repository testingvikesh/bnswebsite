<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OutboundMailer
{
    /**
     * Hostinger blocks/intercepts smtp.gmail.com (535).
     * Send with PHP mail() first, then sendmail. Gmail SMTP is last and optional.
     */
    public function send(string|array $to, Mailable $mailable): void
    {
        $errors = [];
        $addresses = is_array($to) ? $to : [$to];
        $gmailRecipient = collect($addresses)->contains(
            fn ($address) => str_ends_with(strtolower(trim((string) $address)), '@gmail.com')
        );

        $mailers = $this->mailersToTry();
        // Hostinger php/sendmail is "accepted" but Gmail drops it (SPF). Use Google SMTP only.
        if ($gmailRecipient && self::isHostingerServer()) {
            $mailers = ['smtp_ssl'];
        }

        foreach ($mailers as $mailer) {
            try {
                Mail::mailer($mailer)->to($to)->send(clone $mailable);

                Log::info('Outbound mail sent', [
                    'mailer' => $mailer,
                    'to' => $to,
                    'from' => config('mail.from.address'),
                ]);

                return;
            } catch (Throwable $exception) {
                $errors[$mailer] = $exception->getMessage();
                Log::warning('Outbound mailer failed', [
                    'mailer' => $mailer,
                    'to' => $to,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        throw new \RuntimeException($this->summarize($errors));
    }

    /**
     * Hostinger PHP/sendmail is accepted locally, but Gmail drops it (SPF).
     * Authenticate to Google on port 465 first so Gmail actually delivers.
     */
    public function mailersToTry(): array
    {
        if (self::isHostingerServer()) {
            return ['smtp_ssl', 'php', 'sendmail'];
        }

        $default = (string) config('mail.default', 'smtp');

        return array_values(array_unique(array_filter([$default, 'smtp'])));
    }

    public static function isHostingerServer(): bool
    {
        if (filter_var(env('MAIL_USE_SENDMAIL', false), FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }

        $appUrl = strtolower((string) config('app.url', env('APP_URL', '')));
        if (str_contains($appUrl, 'businessnavacharschool.com')) {
            return true;
        }

        $needles = ['hstgr', 'hostinger', 'srv1864255', '/home/businessnavachar'];
        $haystacks = [
            strtolower((string) gethostname()),
            strtolower((string) php_uname('n')),
            strtolower(str_replace('\\', '/', (string) base_path())),
            strtolower(str_replace('\\', '/', (string) ($_SERVER['DOCUMENT_ROOT'] ?? ''))),
        ];

        foreach ($haystacks as $haystack) {
            foreach ($needles as $needle) {
                if ($haystack !== '' && str_contains($haystack, $needle)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  array<string, string>  $errors
     */
    private function summarize(array $errors): string
    {
        if ($errors === []) {
            return 'Unable to send email.';
        }

        $first = (string) reset($errors);
        $lower = strtolower($first);
        if (str_contains($lower, '535') || str_contains($lower, 'authentication failed')) {
            return 'Gmail blocked the school-server mail. Set MAIL_PASSWORD in live .env to a Google App Password for info@businessnavacharschool.com (not the normal G Suite login password): https://myaccount.google.com/apppasswords — then run php artisan optimize:clear and send again.';
        }

        return $first;
    }
}
