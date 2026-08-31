<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use Illuminate\View\View;

class EventsController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function index(): View
    {
        $about = $this->aboutPage->get();
        $page = config('events.page', []);
        $allEvents = collect(config('events.events', []))
            ->filter(function (array $event): bool {
                // Hide introduction sessions once their date/time has passed.
                if (($event['type'] ?? '') === 'introduction' && bns_event_has_passed($event)) {
                    return false;
                }

                return true;
            })
            ->values();
        $spotlightEvents = $allEvents->filter(fn (array $event) => ! empty($event['spotlight']))->values();
        $otherEvents = $allEvents->reject(fn (array $event) => ! empty($event['spotlight']))->values();
        $calendar = config('events.calendar', []);
        $categories = config('events.categories', []);
        $cta = config('events.cta', []);

        return view('events.index', [
            'page' => $page,
            'spotlightEvents' => $spotlightEvents,
            'otherEvents' => $otherEvents,
            'calendar' => $calendar,
            'categories' => $categories,
            'cta' => $cta,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
            'resolveCta' => fn (array $ctaConfig) => $this->resolveCta($ctaConfig),
        ]);
    }

    private function resolveCta(array $ctaConfig): string
    {
        $route = $ctaConfig['route'] ?? 'register';
        $params = $ctaConfig['params'] ?? [];

        return route($route, $params);
    }
}
