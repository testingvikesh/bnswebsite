<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private HomeImageService $homeImages,
        private AboutPageService $aboutPage,
    ) {}

    public function index(): View
    {
        $this->homeImages->syncDefinitionsFromConfig();

        return view('home', [
            'img' => fn (string $key) => $this->homeImages->url($key),
            'about' => $this->aboutPage->get(),
        ]);
    }
}
