<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class EventGallery extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'event_date',
        'cover_path',
        'picasa_url',
        'picasa_label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(EventGalleryPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function reels(): HasMany
    {
        return $this->hasMany(EventGalleryReel::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activePhotos(): HasMany
    {
        return $this->photos()->where('is_active', true);
    }

    public function activeReels(): HasMany
    {
        return $this->reels()->where('is_active', true);
    }

    public static function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'event-gallery';
        $slug = $base;
        $i = 2;

        while (
            static::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function coverUrl(): ?string
    {
        if ($this->cover_path && File::exists(public_path($this->cover_path))) {
            return bns_vasset($this->cover_path);
        }

        $firstPhoto = $this->relationLoaded('activePhotos')
            ? $this->activePhotos->first()
            : $this->activePhotos()->first();

        return $firstPhoto?->url();
    }

    public function deleteUploadedCover(): void
    {
        if (! $this->cover_path) {
            return;
        }

        $fullPath = public_path($this->cover_path);

        if (File::exists($fullPath) && str_starts_with($this->cover_path, 'uploads/event-galleries/')) {
            File::delete($fullPath);
        }
    }

    public function dateLabel(): string
    {
        return $this->event_date ? $this->event_date->format('d M Y') : '';
    }

    public function hasPicasaLink(): bool
    {
        return filled($this->picasa_url);
    }

    public function picasaButtonLabel(): string
    {
        return filled($this->picasa_label)
            ? $this->picasa_label
            : 'Open Full Photo Album (Picasa / Google Photos)';
    }
}
