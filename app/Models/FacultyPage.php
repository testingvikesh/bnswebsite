<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class FacultyPage extends Model
{
    protected $fillable = [
        'page_title',
        'page_subtitle',
        'page_intro',
        'excellence_label',
        'excellence_title',
        'excellence_paragraphs',
        'tagline_brand',
        'tagline_text',
        'hero_image',
        'is_active',
    ];

    protected $casts = [
        'excellence_paragraphs' => 'array',
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
        if ($this->hero_image && str_starts_with($this->hero_image, 'uploads/faculty/')
            && File::exists(public_path($this->hero_image))) {
            File::delete(public_path($this->hero_image));
        }
    }
}
