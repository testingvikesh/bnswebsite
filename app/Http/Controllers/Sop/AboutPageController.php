<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Services\AboutPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutPageController extends Controller
{
    public function __construct(private AboutPageService $aboutPage) {}

    public function edit(): View
    {
        $page = $this->aboutPage->get();

        if (! $page->exists) {
            $page = $this->aboutPage->seedDefault();
        }

        return view('sop.about-page.edit', ['page' => $page]);
    }

    public function update(Request $request): RedirectResponse
    {
        $page = $this->aboutPage->get();

        if (! $page->exists) {
            $page = $this->aboutPage->seedDefault();
        }

        $validated = $request->validate([
            'tagline' => ['required', 'string', 'max:255'],
            'heading' => ['required', 'string', 'max:255'],
            'intro_text' => ['required', 'string', 'max:5000'],
            'focus_heading' => ['nullable', 'string', 'max:255'],
            'focus_points' => ['nullable', 'array'],
            'focus_points.*' => ['nullable', 'string', 'max:500'],
            'quote_text' => ['nullable', 'string', 'max:500'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'mission_title' => ['nullable', 'string', 'max:255'],
            'mission_text' => ['nullable', 'string', 'max:5000'],
            'vision_title' => ['nullable', 'string', 'max:255'],
            'vision_text' => ['nullable', 'string', 'max:5000'],
            'value_titles' => ['nullable', 'array'],
            'value_titles.*' => ['nullable', 'string', 'max:255'],
            'value_texts' => ['nullable', 'array'],
            'value_texts.*' => ['nullable', 'string', 'max:500'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $focusPoints = collect($validated['focus_points'] ?? [])
            ->map(fn ($p) => trim((string) $p))
            ->filter()
            ->values()
            ->all();

        $values = [];
        $titles = $validated['value_titles'] ?? [];
        $texts = $validated['value_texts'] ?? [];
        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            $text = trim((string) ($texts[$i] ?? ''));
            if ($title !== '' || $text !== '') {
                $values[] = ['title' => $title, 'text' => $text];
            }
        }

        if ($request->boolean('remove_hero_image')) {
            $page->deleteHeroImage();
            $page->hero_image = null;
        }

        if ($request->hasFile('hero_image')) {
            $this->aboutPage->storeHeroImage($page, $request->file('hero_image'));
        }

        $page->update([
            'tagline' => $validated['tagline'],
            'heading' => $validated['heading'],
            'intro_text' => $validated['intro_text'],
            'focus_heading' => $validated['focus_heading'],
            'focus_points' => $focusPoints,
            'quote_text' => $validated['quote_text'],
            'video_url' => $validated['video_url'],
            'mission_title' => $validated['mission_title'],
            'mission_text' => $validated['mission_text'],
            'vision_title' => $validated['vision_title'],
            'vision_text' => $validated['vision_text'],
            'values' => $values ?: null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->aboutPage->clearCache();

        return redirect()->route('controlpanel.about-page.edit')
            ->with('status', 'About Us page updated successfully.');
    }
}
