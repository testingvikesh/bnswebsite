<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function index(): View
    {
        $about = $this->aboutPage->get();
        $page = config('statistics.page', []);

        return view('statistics.index', [
            'page' => $page,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }
}
