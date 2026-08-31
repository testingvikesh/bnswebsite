<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    protected $fillable = [
        'full_name',
        'photo_path',
        'designation',
        'organization',
        'location',
        'mobile',
        'email',
        'website',
        'message',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Testimonial $testimonial) {
            $testimonial->deletePhoto();
        });
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path
            ? Storage::disk('public')->url($this->photo_path)
            : null;
    }

    public function getWebsiteUrlAttribute(): ?string
    {
        if (! $this->website) {
            return null;
        }

        $url = trim($this->website);

        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        return $url;
    }

    public function getWebsiteLabelAttribute(): ?string
    {
        if (! $this->website) {
            return null;
        }

        return preg_replace('#^https?://#i', '', rtrim($this->website, '/'));
    }

    public function getMobileTelAttribute(): ?string
    {
        if (! $this->mobile) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $this->mobile);

        return $digits !== '' ? '+'.$digits : null;
    }

    public function deletePhoto(): void
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            Storage::disk('public')->delete($this->photo_path);
        }
    }
}
