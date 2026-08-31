<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\EventGallery;
use App\Models\EventGalleryPhoto;
use App\Models\EventGalleryReel;
use App\Services\EventGalleryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventGalleryController extends Controller
{
    public function __construct(private EventGalleryService $galleries) {}

    public function index(): View
    {
        return view('sop.event-galleries.index', [
            'galleries' => $this->galleries->allForAdmin(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'picasa_url' => ['nullable', 'url', 'max:1000'],
            'picasa_label' => ['nullable', 'string', 'max:255'],
        ]);

        $gallery = EventGallery::query()->create([
            'title' => $validated['title'],
            'slug' => EventGallery::makeUniqueSlug($validated['title']),
            'subtitle' => $validated['subtitle'] ?? null,
            'event_date' => $validated['event_date'] ?? null,
            'picasa_url' => $validated['picasa_url'] ?? null,
            'picasa_label' => $validated['picasa_label'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ((EventGallery::max('sort_order') ?? 0) + 1),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('cover')) {
            $gallery->update([
                'cover_path' => $this->galleries->storeCover($gallery, $request->file('cover')),
            ]);
        }

        return redirect()
            ->route('controlpanel.event-galleries.manage', $gallery)
            ->with('status', 'Event gallery created. Now upload photos and YouTube reels.');
    }

    public function update(Request $request, EventGallery $eventGallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_cover' => ['nullable', 'boolean'],
            'picasa_url' => ['nullable', 'url', 'max:1000'],
            'picasa_label' => ['nullable', 'string', 'max:255'],
        ]);

        $eventGallery->update([
            'title' => $validated['title'],
            'slug' => EventGallery::makeUniqueSlug($validated['title'], $eventGallery->id),
            'subtitle' => $validated['subtitle'] ?? null,
            'event_date' => $validated['event_date'] ?? null,
            'picasa_url' => $validated['picasa_url'] ?? null,
            'picasa_label' => $validated['picasa_label'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $eventGallery->sort_order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->boolean('remove_cover')) {
            $eventGallery->deleteUploadedCover();
            $eventGallery->update(['cover_path' => null]);
        }

        if ($request->hasFile('cover')) {
            $eventGallery->update([
                'cover_path' => $this->galleries->storeCover($eventGallery, $request->file('cover')),
            ]);
        }

        return back()->with('status', 'Event gallery updated.');
    }

    public function destroy(EventGallery $eventGallery): RedirectResponse
    {
        $eventGallery->load(['photos', 'reels']);

        foreach ($eventGallery->photos as $photo) {
            $photo->deleteUploadedPhoto();
        }

        foreach ($eventGallery->reels as $reel) {
            $reel->deleteUploadedThumbnail();
        }

        $eventGallery->deleteUploadedCover();
        $eventGallery->delete();

        return redirect()
            ->route('controlpanel.event-galleries.index')
            ->with('status', 'Event gallery deleted.');
    }

    public function manage(EventGallery $eventGallery): View
    {
        $eventGallery->load([
            'photos' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
            'reels' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
        ]);

        return view('sop.event-galleries.manage', [
            'gallery' => $eventGallery,
        ]);
    }

    public function storePhotos(Request $request, EventGallery $eventGallery): RedirectResponse
    {
        $validated = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $count = $this->galleries->storeMultiplePhotos(
            $eventGallery,
            $request->file('photos', []),
            $validated['title'] ?? null
        );

        return back()->with('status', $count.' photo(s) uploaded successfully.');
    }

    public function updatePhoto(Request $request, EventGallery $eventGallery, EventGalleryPhoto $photo): RedirectResponse
    {
        abort_unless($photo->event_gallery_id === $eventGallery->id, 404);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
        ]);

        $photo->update([
            'title' => $validated['title'] ?? null,
            'caption' => $validated['caption'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $photo->sort_order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('photo')) {
            $photo->deleteUploadedPhoto();
            $photo->update([
                'photo_path' => $this->galleries->storePhoto($eventGallery, $request->file('photo')),
            ]);
        }

        return back()->with('status', 'Photo updated.');
    }

    public function destroyPhoto(EventGallery $eventGallery, EventGalleryPhoto $photo): RedirectResponse
    {
        abort_unless($photo->event_gallery_id === $eventGallery->id, 404);

        $photo->deleteUploadedPhoto();
        $photo->delete();

        return back()->with('status', 'Photo deleted.');
    }

    public function storeReel(Request $request, EventGallery $eventGallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
            'youtube_url' => ['required', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
        ]);

        $reel = EventGalleryReel::query()->create([
            'event_gallery_id' => $eventGallery->id,
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? null,
            'youtube_url' => $validated['youtube_url'],
            'sort_order' => $validated['sort_order'] ?? ((EventGalleryReel::query()->where('event_gallery_id', $eventGallery->id)->max('sort_order') ?? 0) + 1),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('thumbnail')) {
            $reel->update([
                'thumbnail_path' => $this->galleries->storeReelThumbnail($reel, $request->file('thumbnail')),
            ]);
        }

        return back()->with('status', 'YouTube reel added.');
    }

    public function updateReel(Request $request, EventGallery $eventGallery, EventGalleryReel $reel): RedirectResponse
    {
        abort_unless($reel->event_gallery_id === $eventGallery->id, 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
            'youtube_url' => ['required', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_thumbnail' => ['nullable', 'boolean'],
        ]);

        $reel->update([
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? null,
            'youtube_url' => $validated['youtube_url'],
            'sort_order' => $validated['sort_order'] ?? $reel->sort_order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->boolean('remove_thumbnail')) {
            $reel->deleteUploadedThumbnail();
            $reel->update(['thumbnail_path' => null]);
        }

        if ($request->hasFile('thumbnail')) {
            $reel->update([
                'thumbnail_path' => $this->galleries->storeReelThumbnail($reel, $request->file('thumbnail')),
            ]);
        }

        return back()->with('status', 'Reel updated.');
    }

    public function destroyReel(EventGallery $eventGallery, EventGalleryReel $reel): RedirectResponse
    {
        abort_unless($reel->event_gallery_id === $eventGallery->id, 404);

        $reel->deleteUploadedThumbnail();
        $reel->delete();

        return back()->with('status', 'Reel deleted.');
    }
}
