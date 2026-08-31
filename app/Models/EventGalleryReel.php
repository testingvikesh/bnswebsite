<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

class EventGalleryReel extends Model
{
    protected $fillable = [
        'event_gallery_id',
        'title',
        'caption',
        'youtube_url',
        'thumbnail_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(EventGallery::class, 'event_gallery_id');
    }

    public function thumbnailUrl(): string
    {
        if ($this->thumbnail_path && File::exists(public_path($this->thumbnail_path))) {
            return bns_vasset($this->thumbnail_path);
        }

        $fallback = bns_youtube_thumbnail_url($this->youtube_url);

        return $fallback !== '' ? $fallback : bns_vasset('assets/images/logo.png');
    }

    public function deleteUploadedThumbnail(): void
    {
        if (! $this->thumbnail_path) {
            return;
        }

        $fullPath = public_path($this->thumbnail_path);

        if (File::exists($fullPath) && str_starts_with($this->thumbnail_path, 'uploads/event-galleries/')) {
            File::delete($fullPath);
        }
    }
}
