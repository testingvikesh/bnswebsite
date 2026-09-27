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
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Please try submitting the form again. Security check is required.',
            ]);
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
            Log::warning('reCAPTCHA v3 request failed.', ['error' => $e->getMessage()]);

            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Security check could not be completed. Please try again.',
            ]);
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
            $request->header('X-BNS-Security'),
            $request->input('bns_security'),
            $request->input('recaptcha_token'),
            $request->input('g-recaptcha-response'),
        ];

        foreach ($candidates as $value) {
            $token = $this->firstFilledToken($value);
            if ($token !== '') {
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
}
