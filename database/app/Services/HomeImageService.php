<?php

namespace App\Services;

use App\Models\HomePageImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class HomeImageService
{
    /** @var Collection<string, HomePageImage>|null */
    private static ?Collection $cache = null;

    public function url(string $key): string
    {
        $image = $this->get($key);

        return $image ? $image->url() : asset('assets/images/backgrounds/slider-1-1.jpg');
    }

    public function get(string $key): ?HomePageImage
    {
        return $this->all()->get($key);
    }

    /** @return Collection<string, HomePageImage> */
    public function all(): Collection
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        if (! Schema::hasTable('home_page_images')) {
            return self::$cache = collect();
        }

        return self::$cache = HomePageImage::query()
            ->orderBy('sort_order')
            ->get()
            ->keyBy('key');
    }

    /** @return array<string, list<HomePageImage>> */
    public function groupedForAdmin(): array
    {
        $all = $this->all();
        $grouped = [];

        foreach (config('home_images.sections', []) as $section => $items) {
            $grouped[$section] = [];
            foreach (array_keys($items) as $key) {
                if ($record = $all->get($key)) {
                    $grouped[$section][] = $record;
                }
            }
        }

        return $grouped;
    }

    public static function clearCache(): void
    {
        self::$cache = null;
    }

    public function syncDefinitionsFromConfig(): void
    {
        if (! Schema::hasTable('home_page_images')) {
            return;
        }

        $sort = 0;

        foreach (config('home_images.sections', []) as $section => $items) {
            foreach ($items as $key => $meta) {
                HomePageImage::query()->updateOrCreate(
                    ['key' => $key],
                    [
                        'section' => $section,
                        'label' => $meta['label'],
                        'default_path' => $meta['default'],
                        'sort_order' => $sort++,
                    ]
                );
            }
        }

        self::clearCache();
    }

    public function storeUpload(HomePageImage $image, $file): string
    {
        $directory = public_path('uploads/home');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $image->deleteUploadedFile();

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = $image->key.'-'.time().'.'.strtolower($extension);
        $file->move($directory, $filename);

        $relativePath = 'uploads/home/'.$filename;
        $image->update(['image_path' => $relativePath]);
        self::clearCache();

        return $relativePath;
    }

    public function resetToDefault(HomePageImage $image): void
    {
        $image->deleteUploadedFile();
        $image->update(['image_path' => null]);
        self::clearCache();
    }
}
