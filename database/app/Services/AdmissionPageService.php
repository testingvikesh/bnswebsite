<?php

namespace App\Services;

use App\Models\AdmissionPage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class AdmissionPageService
{
    /** @var array<string, AdmissionPage|null> */
    private static array $cache = [];

    public function getBySlug(string $slug): ?AdmissionPage
    {
        if (array_key_exists($slug, self::$cache)) {
            return self::$cache[$slug];
        }

        if (! Schema::hasTable('admission_pages')) {
            return self::$cache[$slug] = $this->defaultPage($slug);
        }

        $page = AdmissionPage::query()->where('slug', $slug)->first();

        if (! $page) {
            $page = $this->seedPage($slug);
        }

        return self::$cache[$slug] = $page;
    }

    /** @return list<AdmissionPage> */
    public function all(): array
    {
        if (! Schema::hasTable('admission_pages')) {
            return collect(config('admission.pages', []))
                ->keys()
                ->map(fn ($slug) => $this->defaultPage($slug))
                ->all();
        }

        $this->seedAllMissing();

        return AdmissionPage::query()->orderBy('id')->get()->all();
    }

    public function clearCache(): void
    {
        self::$cache = [];
    }

    public function seedAllMissing(): void
    {
        foreach (array_keys(config('admission.pages', [])) as $slug) {
            if (! AdmissionPage::query()->where('slug', $slug)->exists()) {
                $this->seedPage($slug);
            }
        }
    }

    public function seedPage(string $slug): AdmissionPage
    {
        $data = config("admission.pages.{$slug}", [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'subtitle' => '',
            'intro' => '',
            'items' => [],
        ]);

        return AdmissionPage::query()->create([
            'slug' => $slug,
            'page_title' => $data['title'],
            'page_subtitle' => $data['subtitle'] ?? null,
            'page_intro' => $data['intro'] ?? '',
            'content_items' => $data['items'] ?? [],
            'download_url' => $data['download_url'] ?? null,
            'is_active' => true,
        ]);
    }

    public function storeHeroImage(AdmissionPage $page, $file): string
    {
        $directory = public_path('uploads/admissions');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if ($page->hero_image && str_starts_with($page->hero_image, 'uploads/admissions/')
            && File::exists(public_path($page->hero_image))) {
            File::delete(public_path($page->hero_image));
        }

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = $page->slug.'-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $path = 'uploads/admissions/'.$filename;
        $page->update(['hero_image' => $path]);
        $this->clearCache();

        return $path;
    }

    private function defaultPage(string $slug): AdmissionPage
    {
        $data = config("admission.pages.{$slug}", [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'subtitle' => '',
            'intro' => '',
            'items' => [],
        ]);

        return new AdmissionPage([
            'slug' => $slug,
            'page_title' => $data['title'],
            'page_subtitle' => $data['subtitle'] ?? null,
            'page_intro' => $data['intro'] ?? '',
            'content_items' => $data['items'] ?? [],
            'download_url' => $data['download_url'] ?? null,
            'is_active' => true,
        ]);
    }
}
