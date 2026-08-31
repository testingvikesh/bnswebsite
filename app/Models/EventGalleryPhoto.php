<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

class EventGalleryPhoto extends Model
{
    protected $fillable = [
        'event_gallery_id',
        'title',
        'caption',
        'photo_path',
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

    public function url(): string
    {
        if ($this->photo_path && File::exists(public_path($this->photo_path))) {
            return bns_vasset($this->photo_path);
        }

        return bns_vasset('assets/images/logo.png');
    }

    public function deleteUploadedPhoto(): void
    {
        if (! $this->photo_path) {
            return;
        }

        $fullPath = public_path($this->photo_path);

        if (File::exists($fullPath) && str_starts_with($this->photo_path, 'uploads/event-galleries/')) {
            File::delete($fullPath);
        }
    }
}
