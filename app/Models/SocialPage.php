<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class SocialPage extends Model
{
    protected $fillable = [
        'page_title', 'page_subtitle', 'page_intro', 'page_intro_2',
        'platforms_title', 'platforms',
        'benefits_title', 'benefits_items',
        'movement_title', 'movement_text', 'movement_text_2',
        'quick_connect_title',
        'tagline_brand', 'tagline_text', 'tagline_subtext', 'tagline_hindi',
        'hero_image', 'is_active',
    ];

    protected $casts = [
        'platforms' => 'array',
        'benefits_items' => 'array',
        'is_active' => 'boolean',
    ];

    public function heroImageUrl(?callable $fallback = null): string
    {
        if ($this->hero_image && File::exists(public_path($this->hero_image))) {
            return bns_vasset($this->hero_image);
        }

        return $fallback ? $fallback() : bns_vasset('assets/images/backgrounds/page-header-bg.jpg');
    }

    public function deleteHeroImage(): void
    {
        if ($this->hero_image && str_starts_with($this->hero_image, 'uploads/social/')
            && File::exists(public_path($this->hero_image))) {
            File::delete(public_path($this->hero_image));
        }
    }
}
