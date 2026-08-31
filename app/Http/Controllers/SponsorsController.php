<?php

namespace App\Http\Controllers;

use App\Services\SponsorPageService;
use App\Services\TeamPageService;
use Illuminate\View\View;

class SponsorsController extends Controller
{
    public function __construct(
        private TeamPageService $teamPage,
        private SponsorPageService $sponsorPage,
    ) {}

    public function index(): View
    {
        $this->sponsorPage->syncFromConfigIfEmpty();
        $teamPage = $this->teamPage->get();

        return view('about.sponsors', [
            'page' => config('team.sponsors_page', []),
            'heroImage' => $teamPage->heroImageUrl(),
            'sponsors' => $this->sponsorPage->buildSponsorsForFront(),
            'venuePartner' => config('team.venue_partner', []),
        ]);
    }
}
