<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use Illuminate\View\View;

class ExpertController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function mehul(): View
    {
        $expert = config('expert_mehul', []);
        $page = $expert['page'] ?? [];

        return view('expert.mehul', [
            'page' => $page,
            'expert' => $expert,
            'heroImage' => $this->aboutPage->get()->heroImageUrl(
                fn () => $this->homeImages->url('about_bg')
            ),
        ]);
    }
}
