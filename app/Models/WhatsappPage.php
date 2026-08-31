<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class WhatsappPage extends Model
{
    protected $fillable = [
        'page_title', 'page_subtitle', 'page_intro', 'page_intro_2',
        'help_title', 'help_intro', 'help_items',
        'chat_title', 'whatsapp_number', 'availability_label', 'availability_hours',
        'quick_options', 'before_chat_title', 'before_chat_intro', 'before_chat_items',
        'one_tap_actions',
        'immediate_title', 'immediate_phone', 'immediate_email',
        'immediate_website', 'immediate_centre_url', 'brochure_url',
        'tagline_brand', 'tagline_text', 'tagline_subtext', 'tagline_hindi',
        'hero_image', 'is_active',
    ];

    protected $casts = [
        'help_items' => 'array',
        'availability_hours' => 'array',
        'quick_options' => 'array',
        'before_chat_items' => 'array',
        'one_tap_actions' => 'array',
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
        if ($this->hero_image && str_starts_with($this->hero_image, 'uploads/whatsapp/')
            && File::exists(public_path($this->hero_image))) {
            File::delete(public_path($this->hero_image));
        }
    }

    public function whatsappDigits(): string
    {
        return preg_replace('/\D+/', '', (string) $this->whatsapp_number) ?: '';
    }

    public function whatsappLink(?string $message = null): string
    {
        $digits = $this->whatsappDigits();
        if ($digits === '') {
            return '#';
        }

        $url = 'https://wa.me/'.$digits;
        if ($message !== null && $message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    public function websiteUrl(): ?string
    {
        if (! $this->immediate_website) {
            return null;
        }

        $url = trim($this->immediate_website);

        return str_starts_with($url, 'http') ? $url : 'https://'.$url;
    }
}
