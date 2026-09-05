<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OutboundMailer
{
    /**
     * Send mail using the working path on this server.
     * Hostinger intercepts smtp.gmail.com, so local sendmail is tried first.
     */
    public function send(string|array $to, Mailable $mailable): void
    {
        $errors = [];

        foreach ($this->mailersToTry() as $mailer) {
            try {
                Mail::mailer($mailer)->to($to)->send($mailable);

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
     * @return list<string>
     */
    public function mailersToTry(): array
    {
        if (self::isHostingerServer()) {
            return ['sendmail', 'smtp'];
        }

        $default = (string) config('mail.default', 'smtp');

        return array_values(array_unique(array_filter([$default, 'smtp'])));
    }

    public static function isHostingerServer(): bool
    {
        if (filter_var(env('MAIL_USE_SENDMAIL', false), FILTER_VALIDATE_BOOLEAN)) {
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
            return 'Email server rejected G Suite SMTP. The site will send through the school server after this update is deployed. Try Send OTP again.';
        }

        return $first;
    }
}
