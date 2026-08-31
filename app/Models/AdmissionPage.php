<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class AdmissionPage extends Model
{
    protected $fillable = [
        'slug', 'page_title', 'page_subtitle', 'page_intro',
        'content_items', 'download_url', 'hero_image', 'is_active',
    ];

    protected $casts = [
        'content_items' => 'array',
        'is_active' => 'boolean',
    ];

    public function heroImageUrl(): string
    {
        if ($this->hero_image && File::exists(public_path($this->hero_image))) {
            return bns_vasset($this->hero_image);
        }

        return bns_vasset('assets/images/backgrounds/page-header-bg.jpg');
    }
}
