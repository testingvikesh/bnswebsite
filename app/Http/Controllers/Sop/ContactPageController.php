<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Services\ContactPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactPageController extends Controller
{
    public function __construct(private ContactPageService $contactPage) {}

    public function edit(): View
    {
        $page = $this->contactPage->get();

        if (! $page->exists) {
            $page = $this->contactPage->seedPage();
        }

        return view('sop.contact-page.edit', ['page' => $page]);
    }

    public function update(Request $request): RedirectResponse
    {
        $page = $this->contactPage->get();

        if (! $page->exists) {
            $page = $this->contactPage->seedPage();
        }

        $validated = $request->validate([
            'page_title' => ['required', 'string', 'max:255'],
            'page_subtitle' => ['nullable', 'string', 'max:255'],
            'page_intro' => ['required', 'string', 'max:5000'],
            'page_intro_2' => ['nullable', 'string', 'max:5000'],
            'office_title' => ['nullable', 'string', 'max:255'],
            'office_brand' => ['nullable', 'string', 'max:255'],
            'office_tagline' => ['nullable', 'string', 'max:255'],
            'office_head_label' => ['nullable', 'string', 'max:255'],
            'address_line' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'pin_code' => ['nullable', 'string', 'max:20'],
            'phone_helpline' => ['nullable', 'string', 'max:30'],
            'phone_whatsapp' => ['nullable', 'string', 'max:30'],
            'phone_office' => ['nullable', 'string', 'max:30'],
            'email_admissions' => ['nullable', 'email', 'max:255'],
            'email_general' => ['nullable', 'email', 'max:255'],
            'email_media' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'office_hours_text' => ['nullable', 'string'],
            'admission_support_title' => ['nullable', 'string', 'max:255'],
            'admission_support_intro' => ['nullable', 'string', 'max:2000'],
            'admission_support_items_text' => ['nullable', 'string'],
            'partnership_title' => ['nullable', 'string', 'max:255'],
            'partnership_intro' => ['nullable', 'string', 'max:2000'],
            'partnership_items_text' => ['nullable', 'string'],
            'faculty_cta_title' => ['nullable', 'string', 'max:255'],
            'faculty_cta_text' => ['nullable', 'string', 'max:2000'],
            'faculty_cta_url' => ['nullable', 'string', 'max:500'],
            'media_title' => ['nullable', 'string', 'max:255'],
            'media_text' => ['nullable', 'string', 'max:2000'],
            'social_labels' => ['nullable', 'array'],
            'social_icons' => ['nullable', 'array'],
            'social_urls' => ['nullable', 'array'],
            'maps_embed_url' => ['nullable', 'string', 'max:2000'],
            'form_categories_text' => ['nullable', 'string'],
            'immediate_title' => ['nullable', 'string', 'max:255'],
            'immediate_call' => ['nullable', 'string', 'max:30'],
            'immediate_whatsapp' => ['nullable', 'string', 'max:30'],
            'immediate_intro_url' => ['nullable', 'string', 'max:500'],
            'immediate_apply_url' => ['nullable', 'string', 'max:500'],
            'tagline_brand' => ['nullable', 'string', 'max:255'],
            'tagline_text' => ['nullable', 'string', 'max:500'],
            'tagline_subtext' => ['nullable', 'string', 'max:500'],
            'tagline_hindi' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $social = [];
        $labels = $validated['social_labels'] ?? [];
        foreach ($labels as $i => $label) {
            $label = trim((string) $label);
            if ($label !== '') {
                $social[] = [
                    'label' => $label,
                    'icon' => trim((string) ($validated['social_icons'][$i] ?? 'fas fa-link')),
                    'url' => trim((string) ($validated['social_urls'][$i] ?? '#')),
                ];
            }
        }

        if ($request->boolean('remove_hero_image')) {
            $page->deleteHeroImage();
            $page->hero_image = null;
        }

        if ($request->hasFile('hero_image')) {
            $this->contactPage->storeHeroImage($page, $request->file('hero_image'));
        }

        $page->update([
            ...collect($validated)->except([
                'office_hours_text', 'admission_support_items_text', 'partnership_items_text',
                'form_categories_text', 'social_labels', 'social_icons', 'social_urls',
                'hero_image', 'remove_hero_image', 'is_active',
            ])->all(),
            'office_hours' => $this->lines($validated['office_hours_text'] ?? ''),
            'admission_support_items' => $this->lines($validated['admission_support_items_text'] ?? ''),
            'partnership_items' => $this->lines($validated['partnership_items_text'] ?? ''),
            'form_categories' => $this->lines($validated['form_categories_text'] ?? ''),
            'social_links' => $social,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->contactPage->clearCache();

        return redirect()->route('controlpanel.contact-page.edit')
            ->with('status', 'Contact page updated successfully.');
    }

    public function inquiries(): View
    {
        $inquiries = ContactInquiry::query()->latest()->paginate(20);

        return view('sop.contact-inquiries.index', compact('inquiries'));
    }

    public function showInquiry(ContactInquiry $inquiry): View
    {
        return view('sop.contact-inquiries.show', compact('inquiry'));
    }

    public function destroyInquiry(ContactInquiry $inquiry): RedirectResponse
    {
        if ($inquiry->documents) {
            foreach ($inquiry->documents as $path) {
                if ($path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }
        }

        $inquiry->delete();

        return back()->with('status', 'Inquiry deleted.');
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
}
