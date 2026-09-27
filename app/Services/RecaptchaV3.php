<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RecaptchaV3
{
    public function enabled(): bool
    {
        return filled(config('services.recaptcha.site_key'))
            && filled(config('services.recaptcha.secret'));
    }

    public function actionForFormSource(?string $formSource): string
    {
        return match ((string) $formSource) {
            'intro-session-modal', 'pay-now-new-registration' => 'intro_session',
            'inquiry-modal' => 'inquiry',
            'register-quick-modal' => 'book_spot',
            'contact-page' => 'contact',
            default => 'contact',
        };
    }

    public function verify(Request $request, ?string $expectedAction = null): void
    {
        if (! $this->enabled()) {
            return;
        }

        $token = $this->tokenFromRequest($request);
        if ($token === '') {
            Log::warning('reCAPTCHA v3 token missing; allowing form submit.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret'),
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA v3 request failed; allowing form submit.', ['error' => $e->getMessage()]);

            return;
        }

        $payload = $response->json();
        $success = is_array($payload) && ! empty($payload['success']);
        $score = is_array($payload) ? (float) ($payload['score'] ?? 0) : 0.0;
        $minScore = (float) config('services.recaptcha.min_score', 0.5);
        $action = is_array($payload) ? (string) ($payload['action'] ?? '') : '';

        $ok = $success && $score >= $minScore;
        if ($ok && $expectedAction && $action !== '' && $action !== $expectedAction) {
            $ok = false;
        }

        if (! $success) {
            Log::info('reCAPTCHA v3 token invalid; allowing form submit.', [
                'score' => $score,
                'action' => $action,
            ]);

            return;
        }

        if (! $ok) {
            Log::info('reCAPTCHA v3 rejected a form.', [
                'success' => $success,
                'score' => $score,
                'action' => $action,
                'expected' => $expectedAction,
            ]);

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Security check failed. Please reload the page and try again.',
            ]);
        }
    }

    private function tokenFromRequest(Request $request): string
    {
        $candidates = [
            $request->header('X-Form-Check'),
            $request->header('X-BNS-Security'),
            $this->tokenFromChunks($request),
            $request->input('bns_security'),
            $request->input('recaptcha_token'),
            $request->input('g-recaptcha-response'),
        ];

        foreach ($candidates as $value) {
            $token = $this->firstFilledToken($value);
            if (strlen($token) >= 20) {
                return $token;
            }
        }

        return '';
    }

    private function firstFilledToken(mixed $value): string
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $token = $this->firstFilledToken($item);
                if ($token !== '') {
                    return $token;
                }
            }

            return '';
        }

        return trim((string) $value);
    }

    private function tokenFromChunks(Request $request): string
    {
        $count = (int) $request->input('fc_count', 0);
        if ($count < 1 || $count > 40) {
            return '';
        }

        $token = '';
        for ($i = 1; $i <= $count; $i++) {
            $token .= (string) $request->input('fc'.$i, '');
        }

        return trim($token);
    }
}
