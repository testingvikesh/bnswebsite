<?php

namespace App\Http\Controllers;

use App\Services\HomeAudienceJourneyService;
use App\Services\HomeImageService;
use App\Services\HomeReelService;
use App\Services\SiteSettingsService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private HomeImageService $homeImages,
        private HomeAudienceJourneyService $audienceJourney,
        private HomeReelService $homeReels,
        private SiteSettingsService $siteSettings,
    ) {}

    public function index(): View
    {
        $this->homeImages->syncDefinitionsFromConfig();
        $this->homeReels->syncFromConfigIfEmpty();

        $hero = config('home.hero', []);
        $heroVideo = $this->siteSettings->heroVideo();
        $hero['video_url'] = $heroVideo['url'];
        $hero['video_label'] = $heroVideo['label'];
        $hero['ctas'] = array_values(array_filter(
            array_map(fn (array $cta) => $this->resolveHeroCta($cta, $heroVideo), $hero['ctas'] ?? []),
            fn (array $cta) => empty($cta['is_video']) || filled($cta['url'] ?? '')
        ));
        $heroHighlights = config('home.hero_highlights', []);
        $audienceSection = config('home.audience_section', []);
        $audienceJourneys = $this->audienceJourney->buildForCards(
            array_values(array_filter(
                $audienceSection['cards'] ?? [],
                fn (array $card) => empty($card['dedicated_page'])
            )),
            $this->siteSettings->brochureMeta()
        );

        return view('home', [
            'img' => fn (string $key) => $this->homeImages->url($key),
            'hero' => $hero,
            'heroHighlights' => $heroHighlights,
            'heroSlides' => config('home.hero_slides', []),
            'audienceSection' => $audienceSection,
            'audienceJourneys' => $audienceJourneys,
            'reelsSection' => $this->homeReels->buildForFront(),
            'contactFormConfig' => config('contact.form', []),
        ]);
    }

    /** @param array<string, mixed> $heroVideo */
    private function resolveHeroCta(array $cta, array $heroVideo): array
    {
        $type = $cta['type'] ?? 'url';

        if ($type === 'video') {
            $cta['url'] = $heroVideo['url'] ?? '';
            if (! empty($heroVideo['label'])) {
                $cta['label'] = $heroVideo['label'];
            }
        }

        $url = match ($type) {
            'route' => route($cta['route'], $cta['params'] ?? []),
            'video' => $cta['url'] ?? '#',
            'whatsapp' => $this->whatsappLink($cta['message'] ?? null),
            default => url($cta['url'] ?? '#'),
        };

        return array_merge($cta, [
            'url' => $url,
            'is_video' => $type === 'video',
            'is_external' => in_array($type, ['whatsapp', 'video'], true),
        ]);
    }

    private function whatsappLink(?string $message = null): string
    {
        return bns_whatsapp_link($message);
    }
}
