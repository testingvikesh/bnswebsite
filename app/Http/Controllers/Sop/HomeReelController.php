<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\HomeReel;
use App\Services\HomeReelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeReelController extends Controller
{
    public function __construct(private HomeReelService $homeReels) {}

    public function index(): View
    {
        $this->homeReels->syncFromConfigIfEmpty();

        $defaults = config('home_reels', []);
        $section = array_merge([
            'enabled' => $defaults['enabled'] ?? true,
            'tagline' => $defaults['tagline'] ?? '',
            'title' => $defaults['title'] ?? '',
            'subtitle' => $defaults['subtitle'] ?? '',
        ], $this->homeReels->sectionSettings());

        return view('sop.home-reels.index', [
            'section' => $section,
            'reels' => $this->homeReels->allForAdmin(),
        ]);
    }

    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'tagline' => ['nullable', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
        ]);

        $this->homeReels->saveSectionSettings([
            'enabled' => $request->boolean('enabled', true),
            'tagline' => $validated['tagline'] ?? '',
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? '',
        ]);

        return back()->with('status', 'Reels section settings updated.');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $reel = HomeReel::query()->create([
            ...$validated,
            'sort_order' => $validated['sort_order'] ?? ((HomeReel::max('sort_order') ?? 0) + 1),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('thumbnail')) {
            $reel->update([
                'thumbnail_path' => $this->homeReels->storeThumbnail($reel, $request->file('thumbnail')),
            ]);
        }

        return back()->with('status', 'Reel added successfully.');
    }

    public function update(Request $request, HomeReel $homeReel): RedirectResponse
    {
        $validated = $this->validated($request, $homeReel->id);

        $homeReel->update([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->boolean('remove_thumbnail')) {
            $homeReel->deleteUploadedThumbnail();
            $homeReel->update(['thumbnail_path' => null]);
        }

        if ($request->hasFile('thumbnail')) {
            $homeReel->update([
                'thumbnail_path' => $this->homeReels->storeThumbnail($homeReel, $request->file('thumbnail')),
            ]);
        }

        return back()->with('status', 'Reel updated successfully.');
    }

    public function destroy(HomeReel $homeReel): RedirectResponse
    {
        $homeReel->deleteUploadedThumbnail();
        $homeReel->delete();

        return back()->with('status', 'Reel deleted.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
            'youtube_url' => ['required', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_thumbnail' => ['nullable', 'boolean'],
        ]);
    }
}
