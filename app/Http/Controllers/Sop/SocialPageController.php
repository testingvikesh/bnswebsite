<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Services\SocialPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocialPageController extends Controller
{
    public function __construct(private SocialPageService $socialPage) {}

    public function edit(): View
    {
        $page = $this->socialPage->get();

        if (! $page->exists) {
            $page = $this->socialPage->seedPage();
        }

        return view('sop.social-page.edit', ['page' => $page]);
    }

    public function update(Request $request): RedirectResponse
    {
        $page = $this->socialPage->get();

        if (! $page->exists) {
            $page = $this->socialPage->seedPage();
        }

        $validated = $request->validate([
            'page_title' => ['required', 'string', 'max:255'],
            'page_subtitle' => ['nullable', 'string', 'max:255'],
            'page_intro' => ['required', 'string', 'max:5000'],
            'page_intro_2' => ['nullable', 'string', 'max:5000'],
            'platforms_title' => ['nullable', 'string', 'max:255'],
            'platforms_text' => ['nullable', 'string'],
            'benefits_title' => ['nullable', 'string', 'max:255'],
            'benefits_items_text' => ['nullable', 'string'],
            'movement_title' => ['nullable', 'string', 'max:255'],
            'movement_text' => ['nullable', 'string', 'max:2000'],
            'movement_text_2' => ['nullable', 'string', 'max:2000'],
            'quick_connect_title' => ['nullable', 'string', 'max:255'],
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
            $this->socialPage->storeHeroImage($page, $request->file('hero_image'));
        }

        $page->update([
            ...collect($validated)->except([
                'platforms_text', 'benefits_items_text',
                'hero_image', 'remove_hero_image', 'is_active',
            ])->all(),
            'platforms' => $this->parsePlatforms($validated['platforms_text'] ?? ''),
            'benefits_items' => $this->lines($validated['benefits_items_text'] ?? ''),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->socialPage->clearCache();

        return redirect()->route('controlpanel.social-page.edit')
            ->with('status', 'Follow Us page updated successfully.');
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

    /** @return list<array<string, string>> */
    private function parsePlatforms(string $text): array
    {
        $platforms = [];

        foreach ($this->lines($text) as $line) {
            $parts = array_map('trim', explode('|', $line, 6));
            if (count($parts) < 4) {
                continue;
            }

            $platforms[] = [
                'icon' => $parts[0] ?? '',
                'name' => $parts[1] ?? '',
                'description' => $parts[2] ?? '',
                'button_label' => $parts[3] ?? ('Follow on '.$parts[1]),
                'url' => $parts[4] ?? '#',
                'accent' => $parts[5] ?? 'default',
            ];
        }

        return $platforms;
    }
}
