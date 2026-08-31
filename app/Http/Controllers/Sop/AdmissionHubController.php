<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Services\AdmissionHubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmissionHubController extends Controller
{
    public function __construct(private AdmissionHubService $admissionHub) {}

    public function edit(): View
    {
        $hub = $this->admissionHub->get();

        if (! $hub->exists) {
            $hub = $this->admissionHub->seedHub();
        }

        return view('sop.admission-hub.edit', ['hub' => $hub]);
    }

    public function update(Request $request): RedirectResponse
    {
        $hub = $this->admissionHub->get();

        if (! $hub->exists) {
            $hub = $this->admissionHub->seedHub();
        }

        $validated = $request->validate([
            'page_title' => ['required', 'string', 'max:255'],
            'page_subtitle' => ['nullable', 'string', 'max:255'],
            'page_intro' => ['required', 'string', 'max:5000'],
            'page_intro_2' => ['nullable', 'string', 'max:5000'],
            'trust_title' => ['nullable', 'string', 'max:255'],
            'trust_items_text' => ['nullable', 'string'],
            'after_admission_title' => ['nullable', 'string', 'max:255'],
            'after_admission_items_text' => ['nullable', 'string'],
            'dashboard_title' => ['nullable', 'string', 'max:255'],
            'dashboard_items_text' => ['nullable', 'string'],
            'office_counselor' => ['nullable', 'string', 'max:255'],
            'office_phone' => ['nullable', 'string', 'max:30'],
            'office_whatsapp' => ['nullable', 'string', 'max:30'],
            'office_email' => ['nullable', 'email', 'max:255'],
            'office_address' => ['nullable', 'string', 'max:500'],
            'maps_embed_url' => ['nullable', 'string', 'max:2000'],
            'tagline_brand' => ['nullable', 'string', 'max:255'],
            'tagline_text' => ['nullable', 'string', 'max:500'],
            'tagline_subtext' => ['nullable', 'string', 'max:500'],
            'tagline_hindi' => ['nullable', 'string', 'max:255'],
            'menu_labels' => ['nullable', 'array'],
            'menu_slugs' => ['nullable', 'array'],
            'menu_icons' => ['nullable', 'array'],
            'menu_groups' => ['nullable', 'array'],
            'menu_descriptions' => ['nullable', 'array'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $menu = [];
        $labels = $validated['menu_labels'] ?? [];
        foreach ($labels as $i => $label) {
            $label = trim((string) $label);
            $slug = trim((string) ($validated['menu_slugs'][$i] ?? ''));
            if ($label !== '' && $slug !== '') {
                $menu[] = [
                    'label' => $label,
                    'slug' => $slug,
                    'icon' => trim((string) ($validated['menu_icons'][$i] ?? 'fas fa-link')),
                    'group' => trim((string) ($validated['menu_groups'][$i] ?? 'Admissions')),
                    'description' => trim((string) ($validated['menu_descriptions'][$i] ?? '')),
                ];
            }
        }

        if ($request->boolean('remove_hero_image')) {
            $hub->deleteHeroImage();
            $hub->hero_image = null;
        }

        if ($request->hasFile('hero_image')) {
            $this->admissionHub->storeHeroImage($hub, $request->file('hero_image'));
        }

        $hub->update([
            'page_title' => $validated['page_title'],
            'page_subtitle' => $validated['page_subtitle'] ?? null,
            'page_intro' => $validated['page_intro'],
            'page_intro_2' => $validated['page_intro_2'] ?? null,
            'menu_items' => $menu,
            'trust_title' => $validated['trust_title'] ?? null,
            'trust_items' => $this->lines($validated['trust_items_text'] ?? ''),
            'after_admission_title' => $validated['after_admission_title'] ?? null,
            'after_admission_items' => $this->lines($validated['after_admission_items_text'] ?? ''),
            'dashboard_title' => $validated['dashboard_title'] ?? null,
            'dashboard_items' => $this->lines($validated['dashboard_items_text'] ?? ''),
            'office_counselor' => $validated['office_counselor'] ?? null,
            'office_phone' => $validated['office_phone'] ?? null,
            'office_whatsapp' => $validated['office_whatsapp'] ?? null,
            'office_email' => $validated['office_email'] ?? null,
            'office_address' => $validated['office_address'] ?? null,
            'maps_embed_url' => $validated['maps_embed_url'] ?? null,
            'tagline_brand' => $validated['tagline_brand'] ?? null,
            'tagline_text' => $validated['tagline_text'] ?? null,
            'tagline_subtext' => $validated['tagline_subtext'] ?? null,
            'tagline_hindi' => $validated['tagline_hindi'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->admissionHub->clearCache();

        return redirect()->route('controlpanel.admission-hub.edit')
            ->with('status', 'Admissions hub page updated successfully.');
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
