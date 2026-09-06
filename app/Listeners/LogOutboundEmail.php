<?php

namespace App\Listeners;

use App\Models\OutboundEmailLog;
use App\Services\OutboundEmailLogService;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Symfony\Component\Mime\Email;
use Throwable;

class LogOutboundEmail
{
    public function __construct(private OutboundEmailLogService $logger) {}

    public function handleSending(MessageSending $event): void
    {
        try {
            $email = $this->symfonyEmail($event);
            if (! $email) {
                return;
            }

            $headers = $email->getHeaders();
            $data = is_array($event->data ?? null) ? $event->data : [];
            $process = $this->logger->processFromViewData($data, (string) $email->getSubject());

            if (! $headers->has('X-BNS-Process')) {
                $headers->addTextHeader('X-BNS-Process', $process);
            }
            if (! $headers->has('X-BNS-Mailer') && $this->logger->currentMailer()) {
                $headers->addTextHeader('X-BNS-Mailer', (string) $this->logger->currentMailer());
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    public function handleSent(MessageSent $event): void
    {
        try {
            $email = $this->symfonyEmail($event);
            if (! $email) {
                return;
            }

            $this->logger->recordFromSymfonyMessage($email, OutboundEmailLog::STATUS_SENT);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function symfonyEmail(object $event): ?Email
    {
        $candidates = [];

        if (isset($event->message)) {
            $candidates[] = $event->message;
        }

        if (isset($event->sent)) {
            $candidates[] = $event->sent;
            if (is_object($event->sent) && method_exists($event->sent, 'getOriginalMessage')) {
                $candidates[] = $event->sent->getOriginalMessage();
            }
        }

        foreach ($candidates as $candidate) {
            if ($candidate instanceof Email) {
                return $candidate;
            }

            if (is_object($candidate) && method_exists($candidate, 'getSymfonyMessage')) {
                $inner = $candidate->getSymfonyMessage();
                if ($inner instanceof Email) {
                    return $inner;
                }
            }

            if (is_object($candidate) && method_exists($candidate, 'getOriginalMessage')) {
                $inner = $candidate->getOriginalMessage();
                if ($inner instanceof Email) {
                    return $inner;
                }
            }
        }

        return null;
    }
}
