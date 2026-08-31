<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Services\WhatsappPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsappPageController extends Controller
{
    public function __construct(private WhatsappPageService $whatsappPage) {}

    public function edit(): View
    {
        $page = $this->whatsappPage->get();

        if (! $page->exists) {
            $page = $this->whatsappPage->seedPage();
        }

        return view('sop.whatsapp-page.edit', ['page' => $page]);
    }

    public function update(Request $request): RedirectResponse
    {
        $page = $this->whatsappPage->get();

        if (! $page->exists) {
            $page = $this->whatsappPage->seedPage();
        }

        $validated = $request->validate([
            'page_title' => ['required', 'string', 'max:255'],
            'page_subtitle' => ['nullable', 'string', 'max:255'],
            'page_intro' => ['required', 'string', 'max:5000'],
            'page_intro_2' => ['nullable', 'string', 'max:5000'],
            'help_title' => ['nullable', 'string', 'max:255'],
            'help_intro' => ['nullable', 'string', 'max:2000'],
            'help_items_text' => ['nullable', 'string'],
            'chat_title' => ['nullable', 'string', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'availability_label' => ['nullable', 'string', 'max:255'],
            'availability_hours_text' => ['nullable', 'string'],
            'quick_options_text' => ['nullable', 'string'],
            'before_chat_title' => ['nullable', 'string', 'max:255'],
            'before_chat_intro' => ['nullable', 'string', 'max:2000'],
            'before_chat_items_text' => ['nullable', 'string'],
            'one_tap_actions_text' => ['nullable', 'string'],
            'immediate_title' => ['nullable', 'string', 'max:255'],
            'immediate_phone' => ['nullable', 'string', 'max:30'],
            'immediate_email' => ['nullable', 'email', 'max:255'],
            'immediate_website' => ['nullable', 'string', 'max:255'],
            'immediate_centre_url' => ['nullable', 'string', 'max:500'],
            'brochure_url' => ['nullable', 'string', 'max:500'],
            'tagline_brand' => ['nullable', 'string', 'max:255'],
            'tagline_text' => ['nullable', 'string', 'max:500'],
            'tagline_subtext' => ['nullable', 'string', 'max:500'],
            'tagline_hindi' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('remove_hero_image')) {
            $page->deleteHeroImage();
            $page->hero_image = null;
        }

        if ($request->hasFile('hero_image')) {
            $this->whatsappPage->storeHeroImage($page, $request->file('hero_image'));
        }

        $page->update([
            ...collect($validated)->except([
                'help_items_text', 'availability_hours_text', 'quick_options_text',
                'before_chat_items_text', 'one_tap_actions_text',
                'hero_image', 'remove_hero_image', 'is_active',
            ])->all(),
            'help_items' => $this->lines($validated['help_items_text'] ?? ''),
            'availability_hours' => $this->lines($validated['availability_hours_text'] ?? ''),
            'quick_options' => $this->parseQuickOptions($validated['quick_options_text'] ?? ''),
            'before_chat_items' => $this->lines($validated['before_chat_items_text'] ?? ''),
            'one_tap_actions' => $this->parseOneTapActions($validated['one_tap_actions_text'] ?? ''),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->whatsappPage->clearCache();

        return redirect()->route('controlpanel.whatsapp-page.edit')
            ->with('status', 'WhatsApp Support page updated successfully.');
    }

    /** @return list<string> */
    private function lines(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values()
            ->all();
    }

    /** @return list<array{icon: string, label: string, message: string}> */
    private function parseQuickOptions(string $text): array
    {
        $options = [];

        foreach ($this->lines($text) as $line) {
            $parts = array_map('trim', explode('|', $line, 3));
            if (count($parts) >= 2) {
                $options[] = [
                    'icon' => $parts[0] ?? '',
                    'label' => $parts[1] ?? '',
                    'message' => $parts[2] ?? ('Hi, I need information about '.$parts[1].' at BNS.'),
                ];
            }
        }

        return $options;
    }

    /** @return list<array<string, string>> */
    private function parseOneTapActions(string $text): array
    {
        $actions = [];

        foreach ($this->lines($text) as $line) {
            $parts = array_map('trim', explode('|', $line, 3));
            if (count($parts) < 2) {
                continue;
            }

            $action = ['label' => $parts[0], 'type' => strtolower($parts[1])];
            if ($action['type'] === 'whatsapp') {
                $action['message'] = $parts[2] ?? 'Hi, I need assistance from BNS.';
            } else {
                $action['url'] = $parts[2] ?? '/';
            }
            $actions[] = $action;
        }

        return $actions;
    }
}
