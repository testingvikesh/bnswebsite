<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Services\AboutPageService;
use App\Services\HomeImageService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function index(): View
    {
        $about = $this->aboutPage->get();

        return view('testimonials.index', [
            'testimonialsPage' => config('testimonials'),
            'testimonials' => $this->testimonials(),
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
        ]);
    }

    private function testimonials(): Collection
    {
        if (Schema::hasTable('testimonials')) {
            $fromDb = Testimonial::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('full_name')
                ->get();

            if ($fromDb->isNotEmpty()) {
                return $fromDb;
            }
        }

        return collect(config('testimonials.items', []))
            ->values()
            ->map(fn (array $item, int $index) => (object) array_merge($item, [
                'sort_order' => $index + 1,
                'organization' => $item['organization'] ?? null,
                'location' => $item['location'] ?? null,
                'mobile' => $item['mobile'] ?? null,
                'email' => $item['email'] ?? null,
                'website' => $item['website'] ?? null,
                'photo_path' => null,
            ]));
    }
}
