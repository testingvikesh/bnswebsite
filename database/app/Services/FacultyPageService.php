<?php

namespace App\Services;

use App\Models\FacultyPage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class FacultyPageService
{
    private static ?FacultyPage $cache = null;

    public function get(): FacultyPage
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (! Schema::hasTable('faculty_pages')) {
            return self::$cache = $this->defaultPage();
        }

        $page = FacultyPage::query()->first();

        return self::$cache = $page ?? $this->seedPage();
    }

    public function clearCache(): void
    {
        self::$cache = null;
    }

    public function seedPage(): FacultyPage
    {
        $config = config('faculty');

        return FacultyPage::query()->create([
            'page_title' => $config['page']['title'],
            'page_subtitle' => $config['page']['subtitle'],
            'page_intro' => $config['page']['intro'],
            'excellence_label' => 'Commitment',
            'excellence_title' => $config['excellence']['title'],
            'excellence_paragraphs' => $config['excellence']['paragraphs'],
            'tagline_brand' => $config['tagline']['brand'],
            'tagline_text' => $config['tagline']['text'],
            'is_active' => true,
        ]);
    }

    public function storeHeroImage(FacultyPage $page, $file): string
    {
        $directory = public_path('uploads/faculty');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $page->deleteHeroImage();

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'hero-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $path = 'uploads/faculty/'.$filename;
        $page->update(['hero_image' => $path]);
        $this->clearCache();

        return $path;
    }

    private function defaultPage(): FacultyPage
    {
        $config = config('faculty');

        return new FacultyPage([
            'page_title' => $config['page']['title'],
            'page_subtitle' => $config['page']['subtitle'],
            'page_intro' => $config['page']['intro'],
            'excellence_label' => 'Commitment',
            'excellence_title' => $config['excellence']['title'],
            'excellence_paragraphs' => $config['excellence']['paragraphs'],
            'tagline_brand' => $config['tagline']['brand'],
            'tagline_text' => $config['tagline']['text'],
            'is_active' => true,
        ]);
    }
}
