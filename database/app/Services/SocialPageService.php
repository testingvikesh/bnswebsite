<?php

namespace App\Services;

use App\Models\SocialPage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SocialPageService
{
    private static ?SocialPage $cache = null;

    public function get(): SocialPage
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (! Schema::hasTable('social_pages')) {
            return self::$cache = $this->defaultPage();
        }

        $page = SocialPage::query()->first();

        return self::$cache = $page ?? $this->seedPage();
    }

    public function clearCache(): void
    {
        self::$cache = null;
    }

    public function seedPage(): SocialPage
    {
        $c = config('social');

        return SocialPage::query()->create([
            'page_title' => $c['page']['title'],
            'page_subtitle' => $c['page']['subtitle'],
            'page_intro' => $c['page']['intro'],
            'page_intro_2' => $c['page']['intro_2'],
            'platforms_title' => $c['platforms']['title'],
            'platforms' => $c['platforms']['items'],
            'benefits_title' => $c['benefits']['title'],
            'benefits_items' => $c['benefits']['items'],
            'movement_title' => $c['movement']['title'],
            'movement_text' => $c['movement']['text'],
            'movement_text_2' => $c['movement']['text_2'],
            'quick_connect_title' => $c['quick_connect']['title'],
            'tagline_brand' => $c['footer_tagline']['brand'],
            'tagline_text' => $c['footer_tagline']['text'],
            'tagline_subtext' => $c['footer_tagline']['subtext'],
            'tagline_hindi' => $c['footer_tagline']['hindi'],
            'is_active' => true,
        ]);
    }

    public function storeHeroImage(SocialPage $page, $file): string
    {
        $directory = public_path('uploads/social');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $page->deleteHeroImage();

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'hero-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $path = 'uploads/social/'.$filename;
        $page->update(['hero_image' => $path]);
        $this->clearCache();

        return $path;
    }

    private function defaultPage(): SocialPage
    {
        $c = config('social');

        return new SocialPage([
            'page_title' => $c['page']['title'],
            'page_subtitle' => $c['page']['subtitle'],
            'page_intro' => $c['page']['intro'],
            'page_intro_2' => $c['page']['intro_2'],
            'platforms_title' => $c['platforms']['title'],
            'platforms' => $c['platforms']['items'],
            'benefits_title' => $c['benefits']['title'],
            'benefits_items' => $c['benefits']['items'],
            'movement_title' => $c['movement']['title'],
            'movement_text' => $c['movement']['text'],
            'movement_text_2' => $c['movement']['text_2'],
            'quick_connect_title' => $c['quick_connect']['title'],
            'tagline_brand' => $c['footer_tagline']['brand'],
            'tagline_text' => $c['footer_tagline']['text'],
            'tagline_subtext' => $c['footer_tagline']['subtext'],
            'tagline_hindi' => $c['footer_tagline']['hindi'],
            'is_active' => true,
        ]);
    }
}
