<?php

namespace App\Services;

use App\Models\WhatsappPage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class WhatsappPageService
{
    private static ?WhatsappPage $cache = null;

    public function get(): WhatsappPage
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (! Schema::hasTable('whatsapp_pages')) {
            return self::$cache = $this->defaultPage();
        }

        $page = WhatsappPage::query()->first();

        return self::$cache = $page ?? $this->seedPage();
    }

    public function clearCache(): void
    {
        self::$cache = null;
    }

    public function seedPage(): WhatsappPage
    {
        $c = config('whatsapp');

        return WhatsappPage::query()->create([
            'page_title' => $c['page']['title'],
            'page_subtitle' => $c['page']['subtitle'],
            'page_intro' => $c['page']['intro'],
            'page_intro_2' => $c['page']['intro_2'],
            'help_title' => $c['help']['title'],
            'help_intro' => $c['help']['intro'],
            'help_items' => $c['help']['items'],
            'chat_title' => $c['chat']['title'],
            'whatsapp_number' => $c['chat']['number'],
            'availability_label' => $c['chat']['availability_label'],
            'availability_hours' => $c['chat']['hours'],
            'quick_options' => $c['quick_options'],
            'before_chat_title' => $c['before_chat']['title'],
            'before_chat_intro' => $c['before_chat']['intro'],
            'before_chat_items' => $c['before_chat']['items'],
            'one_tap_actions' => $c['one_tap'],
            'immediate_title' => $c['immediate']['title'],
            'immediate_phone' => $c['immediate']['phone'],
            'immediate_email' => $c['immediate']['email'],
            'immediate_website' => $c['immediate']['website'],
            'immediate_centre_url' => $c['immediate']['centre_url'],
            'tagline_brand' => $c['footer_tagline']['brand'],
            'tagline_text' => $c['footer_tagline']['text'],
            'tagline_subtext' => $c['footer_tagline']['subtext'],
            'tagline_hindi' => $c['footer_tagline']['hindi'],
            'is_active' => true,
        ]);
    }

    public function storeHeroImage(WhatsappPage $page, $file): string
    {
        $directory = public_path('uploads/whatsapp');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $page->deleteHeroImage();

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'hero-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $path = 'uploads/whatsapp/'.$filename;
        $page->update(['hero_image' => $path]);
        $this->clearCache();

        return $path;
    }

    private function defaultPage(): WhatsappPage
    {
        $c = config('whatsapp');

        return new WhatsappPage([
            'page_title' => $c['page']['title'],
            'page_subtitle' => $c['page']['subtitle'],
            'page_intro' => $c['page']['intro'],
            'page_intro_2' => $c['page']['intro_2'],
            'help_title' => $c['help']['title'],
            'help_intro' => $c['help']['intro'],
            'help_items' => $c['help']['items'],
            'chat_title' => $c['chat']['title'],
            'whatsapp_number' => $c['chat']['number'],
            'availability_label' => $c['chat']['availability_label'],
            'availability_hours' => $c['chat']['hours'],
            'quick_options' => $c['quick_options'],
            'before_chat_title' => $c['before_chat']['title'],
            'before_chat_intro' => $c['before_chat']['intro'],
            'before_chat_items' => $c['before_chat']['items'],
            'one_tap_actions' => $c['one_tap'],
            'immediate_title' => $c['immediate']['title'],
            'immediate_phone' => $c['immediate']['phone'],
            'immediate_email' => $c['immediate']['email'],
            'immediate_website' => $c['immediate']['website'],
            'immediate_centre_url' => $c['immediate']['centre_url'],
            'tagline_brand' => $c['footer_tagline']['brand'],
            'tagline_text' => $c['footer_tagline']['text'],
            'tagline_subtext' => $c['footer_tagline']['subtext'],
            'tagline_hindi' => $c['footer_tagline']['hindi'],
            'is_active' => true,
        ]);
    }
}
