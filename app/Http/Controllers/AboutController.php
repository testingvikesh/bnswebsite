<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function index(): View
    {
        $about = $this->aboutPage->get();
        $hub = config('about.hub', []);
        $valuePreview = array_slice(config('about.values_page.items', []), 0, 6);
        $philosophy = config('about.values_page.philosophy', []);

        return view('about.index', [
            'about' => $about,
            'hub' => $hub,
            'valuePreview' => $valuePreview,
            'philosophy' => $philosophy,
            'shapeImage' => $this->homeImages->url('about_shape'),
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    public function vision(): View
    {
        $about = $this->aboutPage->get();
        $vision = config('about.vision_page', []);

        return view('about.vision', [
            'vision' => $vision,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    public function mission(): View
    {
        $about = $this->aboutPage->get();
        $mission = config('about.mission_page', []);

        return view('about.mission', [
            'mission' => $mission,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    public function values(): View
    {
        $about = $this->aboutPage->get();
        $values = config('about.values_page', []);

        return view('about.values', [
            'values' => $values,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    public function founder(): View
    {
        $about = $this->aboutPage->get();
        $founder = config('about.founder_page', []);

        return view('about.founder', [
            'founder' => $founder,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    public function why(): View
    {
        $about = $this->aboutPage->get();
        $why = config('about.why_bns_page', []);

        return view('about.why', [
            'why' => $why,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    public function whyBusinessEducation(): View
    {
        $about = $this->aboutPage->get();
        $page = config('about.why_business_education_page', []);

        return view('about.why-business-education', [
            'page' => $page,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    public function prosperityMission(): View
    {
        $about = $this->aboutPage->get();
        $page = config('about.prosperity_mission_page', []);

        return view('about.prosperity-mission', [
            'page' => $page,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    public function vision2047(): View
    {
        $about = $this->aboutPage->get();
        $page = config('about.vision_2047_page', []);

        return view('about.vision-2047', [
            'page' => $page,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }
}
