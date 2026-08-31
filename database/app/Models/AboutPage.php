<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class AboutPage extends Model
{
    protected $fillable = [
        'tagline',
        'heading',
        'intro_text',
        'focus_heading',
        'focus_points',
        'quote_text',
        'video_url',
        'hero_image',
        'mission_title',
        'mission_text',
        'vision_title',
        'vision_text',
        'values',
        'is_active',
    ];

    protected $casts = [
        'focus_points' => 'array',
        'values' => 'array',
        'is_active' => 'boolean',
    ];

    public function heroImageUrl(?callable $fallback = null): string
    {
        if ($this->hero_image && File::exists(public_path($this->hero_image))) {
            return asset($this->hero_image);
        }

        return $fallback ? $fallback() : asset('assets/images/backgrounds/about-one-bg.jpg');
    }

    public function deleteHeroImage(): void
    {
        if ($this->hero_image && str_starts_with($this->hero_image, 'uploads/about/')
            && File::exists(public_path($this->hero_image))) {
            File::delete(public_path($this->hero_image));
        }
    }
}
