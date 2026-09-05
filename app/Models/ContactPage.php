<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ContactPage extends Model
{
    protected $fillable = [
        'page_title', 'page_subtitle', 'page_intro', 'page_intro_2',
        'office_title', 'office_brand', 'office_tagline', 'office_head_label',
        'address_line', 'city', 'state', 'pin_code',
        'phone_helpline', 'phone_whatsapp', 'phone_office',
        'email_admissions', 'email_general', 'email_media',
        'website', 'office_hours',
        'admission_support_title', 'admission_support_intro', 'admission_support_items',
        'partnership_title', 'partnership_intro', 'partnership_items',
        'faculty_cta_title', 'faculty_cta_text', 'faculty_cta_url',
        'media_title', 'media_text',
        'social_links', 'maps_embed_url', 'form_categories',
        'immediate_title', 'immediate_call', 'immediate_whatsapp',
        'immediate_intro_url', 'immediate_apply_url',
        'tagline_brand', 'tagline_text', 'tagline_subtext', 'tagline_hindi',
        'hero_image', 'is_active',
    ];

    protected $casts = [
        'office_hours' => 'array',
        'admission_support_items' => 'array',
        'partnership_items' => 'array',
        'social_links' => 'array',
        'form_categories' => 'array',
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
        if ($this->hero_image && str_starts_with($this->hero_image, 'uploads/contact/')
            && File::exists(public_path($this->hero_image))) {
            File::delete(public_path($this->hero_image));
        }
    }

    public function websiteUrl(): ?string
    {
        if (! $this->website) {
            return null;
        }

        $url = trim($this->website);

        return str_starts_with($url, 'http') ? $url : 'https://'.$url;
    }

    public function mapsUrl(): string
    {
        $configured = trim((string) config('contact.maps_url', ''));
        if ($configured !== '') {
            return $configured;
        }

        $embed = trim((string) $this->maps_embed_url);
        if ($embed !== '' && str_contains($embed, 'google.com/maps') && ! str_contains($embed, '0x0%3A0x0')) {
            $clickable = preg_replace('/([?&])output=embed(&|$)/', '$1', $embed) ?: $embed;

            return rtrim($clickable, '?&');
        }

        $query = trim(implode(', ', array_filter([
            $this->address_line,
            $this->city,
            $this->state,
            $this->pin_code,
            'Business Navachar School',
        ], fn ($value) => filled($value))));

        return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($query);
    }

    public function mapsEmbedSrc(): ?string
    {
        $embed = trim((string) $this->maps_embed_url);
        $fallback = trim((string) config('contact.maps_embed_url', ''));

        if ($embed === '' || str_contains($embed, '0x0%3A0x0') || ! str_contains($embed, 'google.com/maps')) {
            $embed = $fallback;
        }

        return $embed !== '' ? $embed : null;
    }
}
