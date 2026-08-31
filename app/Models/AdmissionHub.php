<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class AdmissionHub extends Model
{
    protected $table = 'admission_hub';

    protected $fillable = [
        'page_title', 'page_subtitle', 'page_intro', 'page_intro_2',
        'menu_items', 'trust_title', 'trust_items',
        'after_admission_title', 'after_admission_items',
        'dashboard_title', 'dashboard_items',
        'office_counselor', 'office_phone', 'office_whatsapp',
        'office_email', 'office_address', 'maps_embed_url',
        'tagline_brand', 'tagline_text', 'tagline_subtext', 'tagline_hindi',
        'hero_image', 'is_active',
    ];

    protected $casts = [
        'menu_items' => 'array',
        'trust_items' => 'array',
        'after_admission_items' => 'array',
        'dashboard_items' => 'array',
        'is_active' => 'boolean',
    ];

    public function heroImageUrl(): string
    {
        if ($this->hero_image && File::exists(public_path($this->hero_image))) {
            return bns_vasset($this->hero_image);
        }

        return bns_vasset('assets/images/backgrounds/page-header-bg.jpg');
    }

    public function deleteHeroImage(): void
    {
        if ($this->hero_image && str_starts_with($this->hero_image, 'uploads/admissions/')
            && File::exists(public_path($this->hero_image))) {
            File::delete(public_path($this->hero_image));
        }
    }

    /** @return array<string, list<array<string, string>>> */
    public function groupedMenu(): array
    {
        $groups = [];
        foreach ($this->menu_items ?? [] as $item) {
            if (($item['slug'] ?? '') === 'download-brochure') {
                continue;
            }
            $group = $item['group'] ?? 'Admissions';
            $groups[$group][] = $item;
        }

        return $groups;
    }

    public function menuUrl(array $item): string
    {
        if (($item['slug'] ?? '') === 'apply-now') {
            return route('register');
        }

        return route('admissions.page', $item['slug']);
    }
}
