<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class HomeReel extends Model
{
    protected $fillable = [
        'title',
        'caption',
        'youtube_url',
        'thumbnail_path',
        'default_thumbnail',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function thumbnailUrl(): string
    {
        if ($this->thumbnail_path && File::exists(public_path($this->thumbnail_path))) {
            return bns_vasset($this->thumbnail_path);
        }

        if ($this->default_thumbnail && File::exists(public_path($this->default_thumbnail))) {
            return bns_vasset($this->default_thumbnail);
        }

        $fallback = bns_youtube_thumbnail_url($this->youtube_url);

        return $fallback !== '' ? $fallback : bns_vasset('assets/images/logo.png');
    }

    public function hasCustomThumbnail(): bool
    {
        return filled($this->thumbnail_path) && File::exists(public_path($this->thumbnail_path));
    }

    public function deleteUploadedThumbnail(): void
    {
        if (! $this->thumbnail_path) {
            return;
        }

        $fullPath = public_path($this->thumbnail_path);

        if (File::exists($fullPath) && str_starts_with($this->thumbnail_path, 'uploads/home/reels/')) {
            File::delete($fullPath);
        }
    }

    /** @return array<string, mixed> */
    public function toFrontArray(): array
    {
        return [
            'id' => $this->id,
            'youtube_url' => $this->youtube_url,
            'thumbnail' => $this->thumbnail_path ?: $this->default_thumbnail,
            'title' => $this->title,
            'caption' => $this->caption,
        ];
    }
}
