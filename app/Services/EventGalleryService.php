<?php

namespace App\Services;

use App\Models\EventGallery;
use App\Models\EventGalleryPhoto;
use App\Models\EventGalleryReel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class EventGalleryService
{
    /** @return Collection<int, EventGallery> */
    public function allForAdmin(): Collection
    {
        if (! Schema::hasTable('event_galleries')) {
            return collect();
        }

        return EventGallery::query()
            ->withCount(['photos', 'reels'])
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->orderBy('id')
            ->get();
    }

    /** @return Collection<int, EventGallery> */
    public function activeForFront(): Collection
    {
        if (! Schema::hasTable('event_galleries')) {
            return collect();
        }

        return EventGallery::query()
            ->where('is_active', true)
            ->with([
                'activePhotos' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
                'activeReels' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
            ])
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->orderBy('id')
            ->get()
            ->filter(fn (EventGallery $gallery) => $gallery->activePhotos->isNotEmpty()
                || $gallery->activeReels->isNotEmpty()
                || $gallery->hasPicasaLink())
            ->values();
    }

    public function storeCover(EventGallery $gallery, UploadedFile $file): string
    {
        $directory = public_path('uploads/event-galleries/'.$gallery->id.'/cover');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $gallery->deleteUploadedCover();

        $filename = 'cover-'.$gallery->id.'-'.time().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/event-galleries/'.$gallery->id.'/cover/'.$filename;
    }

    public function storePhoto(EventGallery $gallery, UploadedFile $file): string
    {
        $directory = public_path('uploads/event-galleries/'.$gallery->id.'/photos');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = 'photo-'.time().'-'.mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/event-galleries/'.$gallery->id.'/photos/'.$filename;
    }

    public function storeReelThumbnail(EventGalleryReel $reel, UploadedFile $file): string
    {
        $directory = public_path('uploads/event-galleries/'.$reel->event_gallery_id.'/reels');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $reel->deleteUploadedThumbnail();

        $filename = 'reel-'.$reel->id.'-'.time().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/event-galleries/'.$reel->event_gallery_id.'/reels/'.$filename;
    }

    /** @param  array<int, UploadedFile>  $files */
    public function storeMultiplePhotos(EventGallery $gallery, array $files, ?string $title = null): int
    {
        $count = 0;
        $sort = (int) (EventGalleryPhoto::query()->where('event_gallery_id', $gallery->id)->max('sort_order') ?? 0);

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $sort++;
            EventGalleryPhoto::query()->create([
                'event_gallery_id' => $gallery->id,
                'title' => $title,
                'photo_path' => $this->storePhoto($gallery, $file),
                'sort_order' => $sort,
                'is_active' => true,
            ]);
            $count++;
        }

        return $count;
    }
}
