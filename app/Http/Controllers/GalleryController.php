<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\EventGalleryService;
use App\Services\HomeImageService;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(
        private EventGalleryService $galleries,
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function index(): View
    {
        $page = config('gallery.page', []);
        $events = $this->galleries->activeForFront();

        return view('gallery.index', [
            'page' => $page,
            'events' => $events,
            'heroImage' => $this->aboutPage->get()->heroImageUrl(
                fn () => $this->homeImages->url('about_bg')
            ),
        ]);
    }
}
