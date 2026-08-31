<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\SponsorMember;
use App\Services\SponsorPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SponsorMemberController extends Controller
{
    public function __construct(private SponsorPageService $sponsors) {}

    public function index(): View
    {
        $this->sponsors->syncFromConfigIfEmpty();

        $defaults = config('team.sponsors', []);
        $section = array_merge([
            'title' => $defaults['title'] ?? 'Meet Our Sponsors',
            'subtitle' => $defaults['subtitle'] ?? '',
            'section_label' => $defaults['section_label'] ?? 'Partners',
        ], $this->sponsors->sectionSettings());

        return view('sop.sponsor-members.index', [
            'section' => $section,
            'members' => $this->sponsors->allForAdmin(),
        ]);
    }

    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'section_label' => ['nullable', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
        ]);

        $this->sponsors->saveSectionSettings([
            'section_label' => $validated['section_label'] ?? '',
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? '',
        ]);

        return back()->with('status', 'Sponsors section settings updated.');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $member = SponsorMember::query()->create([
            ...$validated,
            'sort_order' => $validated['sort_order'] ?? ((SponsorMember::max('sort_order') ?? 0) + 1),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('photo')) {
            $member->update([
                'photo_path' => $this->sponsors->storePhoto($member, $request->file('photo')),
            ]);
        }

        return back()->with('status', 'Sponsor added successfully.');
    }

    public function update(Request $request, SponsorMember $sponsorMember): RedirectResponse
    {
        $validated = $this->validated($request);

        $sponsorMember->update([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->boolean('remove_photo')) {
            $sponsorMember->deleteUploadedPhoto();
            $sponsorMember->update(['photo_path' => null]);
        }

        if ($request->hasFile('photo')) {
            $sponsorMember->update([
                'photo_path' => $this->sponsors->storePhoto($sponsorMember, $request->file('photo')),
            ]);
        }

        return back()->with('status', 'Sponsor updated successfully.');
    }

    public function destroy(SponsorMember $sponsorMember): RedirectResponse
    {
        $sponsorMember->deleteUploadedPhoto();
        $sponsorMember->delete();

        return back()->with('status', 'Sponsor deleted.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'profile' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);
    }
}
