<?php

namespace App\Services;

use App\Models\AboutPage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class AboutPageService
{
    private static ?AboutPage $cache = null;

    public function get(): AboutPage
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (! Schema::hasTable('about_pages')) {
            return self::$cache = $this->defaultModel();
        }

        $page = AboutPage::query()->first();

        return self::$cache = $page ?? $this->seedDefault();
    }

    public function clearCache(): void
    {
        self::$cache = null;
    }

    public function seedDefault(): AboutPage
    {
        $defaults = config('about');

        return AboutPage::query()->create([
            'tagline' => $defaults['tagline'],
            'heading' => $defaults['heading'],
            'intro_text' => $defaults['intro_text'],
            'focus_heading' => $defaults['focus_heading'],
            'focus_points' => $defaults['focus_points'],
            'quote_text' => $defaults['quote_text'],
            'video_url' => $defaults['video_url'],
            'mission_title' => $defaults['mission_title'],
            'mission_text' => $defaults['mission_text'],
            'vision_title' => $defaults['vision_title'],
            'vision_text' => $defaults['vision_text'],
            'values' => $defaults['values'],
            'is_active' => true,
        ]);
    }

    private function defaultModel(): AboutPage
    {
        $defaults = config('about');

        return new AboutPage([
            'tagline' => $defaults['tagline'],
            'heading' => $defaults['heading'],
            'intro_text' => $defaults['intro_text'],
            'focus_heading' => $defaults['focus_heading'],
            'focus_points' => $defaults['focus_points'],
            'quote_text' => $defaults['quote_text'],
            'video_url' => $defaults['video_url'],
            'mission_title' => $defaults['mission_title'],
            'mission_text' => $defaults['mission_text'],
            'vision_title' => $defaults['vision_title'],
            'vision_text' => $defaults['vision_text'],
            'values' => $defaults['values'],
            'is_active' => true,
        ]);
    }

    public function storeHeroImage(AboutPage $page, $file): string
    {
        $directory = public_path('uploads/about');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $page->deleteHeroImage();

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'hero-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $path = 'uploads/about/'.$filename;
        $page->update(['hero_image' => $path]);
        $this->clearCache();

        return $path;
    }
}
