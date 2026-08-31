<?php

namespace App\Support;

use Illuminate\Support\Facades\View;

class MessageEmailHtmlBuilder
{
    /**
     * Layout view names that must not be used as message layout keys.
     *
     * @var list<string>
     */
    private const SKIP_LAYOUT_VIEWS = [
        'smart',
        'plain',
        '_checklist',
        '_hero-checklist',
        '_payload-remainder',
        '_ui-sessions',
        '_ui-topics',
        '_ui-venue-simple',
    ];

    /**
     * @param  array<string, mixed>  $item  Full communication-sequence message item
     */
    public function render(array $item): string
    {
        try {
            return $this->renderInternal($item);
        } catch (\Throwable $e) {
            report($e);

            return view('emails.layouts.plain', [
                'title' => (string) ($item['title'] ?? 'BNS Message'),
                'eyebrow' => (string) ($item['layout'] ?? 'BNS Message'),
                'bodyHtml' => bns_whatsapp_text_to_email_html($this->fullWhatsapp($item)),
            ])->render();
        }
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function renderInternal(array $item): string
    {
        $layout = trim((string) ($item['layout'] ?? ''));
        $payload = $this->payloadFor($item, $layout);

        // Prefer dedicated blade matching the web modal UI (promo, about, venue, …)
        if ($layout !== '' && $payload !== null && $payload !== [] && ! in_array($layout, self::SKIP_LAYOUT_VIEWS, true)) {
            $view = 'emails.layouts.'.$layout;
            if (View::exists($view)) {
                return view($view, [
                    'data' => $payload,
                    'item' => $item,
                ])->render();
            }
        }

        // Universal attractive renderer for every structured template
        if ($payload !== null && $payload !== []) {
            return view('emails.layouts.smart', [
                'data' => $payload,
                'item' => $item,
            ])->render();
        }

        // Full WhatsApp text fallback (never truncate)
        return view('emails.layouts.plain', [
            'title' => (string) ($item['title'] ?? 'BNS Message'),
            'eyebrow' => $layout !== '' ? $layout : 'BNS Message',
            'bodyHtml' => bns_whatsapp_text_to_email_html($this->fullWhatsapp($item)),
        ])->render();
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>|null
     */
    private function payloadFor(array $item, string $layout): ?array
    {
        if ($layout !== '' && isset($item[$layout]) && is_array($item[$layout]) && $item[$layout] !== []) {
            return $item[$layout];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function fullWhatsapp(array $item): string
    {
        $whatsapp = trim((string) ($item['whatsapp'] ?? ''));
        if ($whatsapp !== '') {
            return $whatsapp;
        }

        if (! empty($item['body']) && is_array($item['body'])) {
            return collect($item['body'])
                ->map(fn ($line) => trim(strip_tags((string) $line)))
                ->filter()
                ->implode("\n\n");
        }

        return '';
    }
}
