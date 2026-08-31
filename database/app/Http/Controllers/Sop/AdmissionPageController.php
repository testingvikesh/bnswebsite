<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\AdmissionPage;
use App\Services\AdmissionPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmissionPageController extends Controller
{
    public function __construct(private AdmissionPageService $admissionPages) {}

    public function index(): View
    {
        $pages = $this->admissionPages->all();

        return view('sop.admission-pages.index', compact('pages'));
    }

    public function edit(AdmissionPage $admission_page): View
    {
        return view('sop.admission-pages.edit', ['page' => $admission_page]);
    }

    public function update(Request $request, AdmissionPage $admission_page): RedirectResponse
    {
        $validated = $request->validate([
            'page_title' => ['required', 'string', 'max:255'],
            'page_subtitle' => ['nullable', 'string', 'max:255'],
            'page_intro' => ['nullable', 'string', 'max:5000'],
            'content_items_text' => ['nullable', 'string'],
            'download_url' => ['nullable', 'string', 'max:500'],
            'hero_image' => ['nullable', 'image', 'max:8192'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('hero_image')) {
            $this->admissionPages->storeHeroImage($admission_page, $request->file('hero_image'));
        }

        $admission_page->update([
            'page_title' => $validated['page_title'],
            'page_subtitle' => $validated['page_subtitle'] ?? null,
            'page_intro' => $validated['page_intro'] ?? '',
            'content_items' => $this->lines($validated['content_items_text'] ?? ''),
            'download_url' => $validated['download_url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->admissionPages->clearCache();

        return back()->with('status', 'Page updated.');
    }

    public function applications(): View
    {
        $applications = AdmissionApplication::query()->latest()->paginate(20);

        return view('sop.admission-applications.index', compact('applications'));
    }

    public function showApplication(AdmissionApplication $application): View
    {
        return view('sop.admission-applications.show', compact('application'));
    }

    public function destroyApplication(AdmissionApplication $application): RedirectResponse
    {
        $application->delete();

        return back()->with('status', 'Application deleted.');
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
