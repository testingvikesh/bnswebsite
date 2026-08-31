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

        return view('about.index', [
            'about' => $about,
            'shapeImage' => $this->homeImages->url('about_shape'),
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }
}
