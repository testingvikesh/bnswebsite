<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeAudienceJourneyService;
use App\Services\HomeImageService;
use App\Services\SiteSettingsService;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AudienceProgramController extends Controller
{
    public function __construct(
        private HomeAudienceJourneyService $audienceJourney,
        private SiteSettingsService $siteSettings,
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function show(string $slug): View|Response
    {
        $program = config("audience_programs.{$slug}");
        if (! $program) {
            abort(404);
        }

        $card = collect(config('home.audience_section.cards', []))
            ->first(fn (array $item) => ($item['program_slug'] ?? '') === $slug);

        if (! $card || empty($card['register_hash'])) {
            abort(404);
        }

        $journeys = $this->audienceJourney->buildForCards(
            [$card],
            $this->siteSettings->brochureMeta()
        );

        $journey = $journeys[$card['register_hash']] ?? null;
        if (! $journey) {
            abort(404);
        }

        if (! empty($program['boxes'])) {
            $program['boxes'] = bns_order_program_boxes($program['boxes']);
        }

        return view('programs.audience.show', [
            'slug' => $slug,
            'program' => $program,
            'card' => $card,
            'journey' => $journey,
            'contactFormConfig' => config('contact.form', []),
            'heroImage' => $this->aboutPage->get()->heroImageUrl(
                fn () => $this->homeImages->url('about_bg')
            ),
        ]);
    }
}
