<?php

namespace App\Services;

use App\Models\HomeReel;
use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class HomeReelService
{
    public const SECTION_SETTING_KEY = 'home_reels_section';

    /** @return array<string, mixed> */
    public function buildForFront(): array
    {
        $defaults = config('home_reels', []);
        $section = $this->sectionSettings();

        $reels = $this->activeReels();

        if ($reels->isEmpty()) {
            $reels = collect($defaults['reels'] ?? []);
        } else {
            $reels = $reels->map(fn (HomeReel $reel) => $reel->toFrontArray());
        }

        return [
            'enabled' => (bool) ($section['enabled'] ?? $defaults['enabled'] ?? true),
            'tagline' => $section['tagline'] ?? $defaults['tagline'] ?? 'BNS Reels',
            'title' => $section['title'] ?? $defaults['title'] ?? 'Watch & Learn with BNS',
            'subtitle' => $section['subtitle'] ?? $defaults['subtitle'] ?? '',
            'reels' => $reels->values()->all(),
            'brochure_cta' => $defaults['brochure_cta'] ?? ['enabled' => false],
        ];
    }

    public function syncFromConfigIfEmpty(): void
    {
        if (! Schema::hasTable('home_reels') || HomeReel::query()->exists()) {
            return;
        }

        foreach (config('home_reels.reels', []) as $index => $reel) {
            HomeReel::query()->create([
                'title' => $reel['title'] ?? 'BNS Reel',
                'caption' => $reel['caption'] ?? null,
                'youtube_url' => $reel['youtube_url'] ?? '',
                'default_thumbnail' => $reel['thumbnail'] ?? null,
                'sort_order' => $reel['id'] ?? ($index + 1),
                'is_active' => true,
            ]);
        }
    }

    /** @return array<string, mixed> */
    public function sectionSettings(): array
    {
        if (! Schema::hasTable('site_settings')) {
            return [];
        }

        $raw = SiteSetting::query()->where('key', self::SECTION_SETTING_KEY)->value('value');

        if (! is_string($raw) || trim($raw) === '') {
            return [];
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    /** @param array<string, mixed> $settings */
    public function saveSectionSettings(array $settings): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        SiteSetting::query()->updateOrCreate(
            ['key' => self::SECTION_SETTING_KEY],
            ['value' => json_encode($settings, JSON_UNESCAPED_UNICODE)]
        );
    }

    /** @return \Illuminate\Support\Collection<int, HomeReel> */
    public function allForAdmin()
    {
        if (! Schema::hasTable('home_reels')) {
            return collect();
        }

        return HomeReel::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /** @return \Illuminate\Support\Collection<int, HomeReel> */
    private function activeReels()
    {
        if (! Schema::hasTable('home_reels')) {
            return collect();
        }

        return HomeReel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function storeThumbnail(HomeReel $reel, UploadedFile $file): string
    {
        $directory = public_path('uploads/home/reels');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $reel->deleteUploadedThumbnail();

        $filename = 'reel-'.$reel->id.'-'.time().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'uploads/home/reels/'.$filename;
    }
}
