<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Services\FacultyPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacultyPageController extends Controller
{
    public function __construct(private FacultyPageService $facultyPage) {}

    public function edit(): View
    {
        $page = $this->facultyPage->get();

        if (! $page->exists) {
            $page = $this->facultyPage->seedPage();
        }

        return view('sop.faculty-page.edit', ['page' => $page]);
    }

    public function update(Request $request): RedirectResponse
    {
        $page = $this->facultyPage->get();

        if (! $page->exists) {
            $page = $this->facultyPage->seedPage();
        }

        $validated = $request->validate([
            'page_title' => ['required', 'string', 'max:255'],
            'page_subtitle' => ['nullable', 'string', 'max:255'],
            'page_intro' => ['required', 'string', 'max:5000'],
            'excellence_label' => ['nullable', 'string', 'max:255'],
            'excellence_title' => ['required', 'string', 'max:255'],
            'excellence_paragraphs' => ['nullable', 'array'],
            'excellence_paragraphs.*' => ['nullable', 'string', 'max:2000'],
            'tagline_brand' => ['nullable', 'string', 'max:255'],
            'tagline_text' => ['nullable', 'string', 'max:500'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $paragraphs = collect($validated['excellence_paragraphs'] ?? [])
            ->map(fn ($p) => trim((string) $p))
            ->filter()
            ->values()
            ->all();

        if ($request->boolean('remove_hero_image')) {
            $page->deleteHeroImage();
            $page->hero_image = null;
        }

        if ($request->hasFile('hero_image')) {
            $this->facultyPage->storeHeroImage($page, $request->file('hero_image'));
        }

        $page->update([
            'page_title' => $validated['page_title'],
            'page_subtitle' => $validated['page_subtitle'],
            'page_intro' => $validated['page_intro'],
            'excellence_label' => $validated['excellence_label'],
            'excellence_title' => $validated['excellence_title'],
            'excellence_paragraphs' => $paragraphs,
            'tagline_brand' => $validated['tagline_brand'],
            'tagline_text' => $validated['tagline_text'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->facultyPage->clearCache();

        return redirect()->route('controlpanel.faculty-page.edit')
            ->with('status', 'Faculty page updated successfully.');
    }
}
