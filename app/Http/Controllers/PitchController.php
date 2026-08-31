<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PitchController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function index(): View
    {
        return view('pitch.index', [
            'page' => config('pitch.page', []),
            'orientationPitch' => config('home_orientation_pitch', []),
            'coachPresentation' => config('home_coach_presentation', []),
            'memberPitch' => config('business_coach_pitch', []),
            'heroImage' => $this->heroImage(),
        ]);
    }

    public function businessCoach(): RedirectResponse
    {
        return redirect()->route('pitch.bns-member', [], 301);
    }

    public function bnsMember(): View
    {
        return view('pitch.bns-member', [
            'page' => config('business_coach_pitch.page', []),
            'pitch' => config('business_coach_pitch', []),
            'heroImage' => $this->heroImage(),
        ]);
    }

    private function heroImage(): string
    {
        return $this->aboutPage->get()->heroImageUrl(
            fn () => $this->homeImages->url('about_bg')
        );
    }
}
