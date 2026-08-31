<?php

namespace App\Http\Controllers;

use App\Services\AboutPageService;
use App\Services\HomeImageService;
use Illuminate\View\View;

class ProgramsController extends Controller
{
    public function __construct(
        private AboutPageService $aboutPage,
        private HomeImageService $homeImages,
    ) {}

    public function featured(): View
    {
        $about = $this->aboutPage->get();
        $page = config('programs.featured_page', []);
        $audienceCards = collect(config('home.audience_section.cards', []))
            ->keyBy('program_slug');

        return view('programs.featured', [
            'page' => $page,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
            'audienceCards' => $audienceCards,
        ]);
    }

    public function syllabus(): View
    {
        $about = $this->aboutPage->get();
        $featuredPrograms = config('programs.featured_page.programs', []);
        $audiencePrograms = config('audience_programs', []);

        $programs = collect($featuredPrograms)
            ->filter(function (array $program) use ($audiencePrograms) {
                $slug = $program['slug'] ?? '';

                return $slug !== ''
                    && isset($audiencePrograms[$slug])
                    && ! empty($audiencePrograms[$slug]['program_structure']);
            })
            ->map(function (array $program) use ($audiencePrograms) {
                $slug = $program['slug'];
                $full = $audiencePrograms[$slug];

                return [
                    'slug' => $slug,
                    'icon' => $program['icon'] ?? ($full['icon'] ?? '📚'),
                    'title' => $program['title'] ?? ($full['title'] ?? $slug),
                    'audience' => $program['audience'] ?? '',
                    'summary' => $program['summary'] ?? ($full['intro'] ?? ''),
                    'duration' => $program['duration'] ?? '',
                    'program' => $full,
                ];
            })
            ->values()
            ->all();

        return view('programs.syllabus', [
            'programs' => $programs,
            'heroImage' => $about->heroImageUrl(fn () => $this->homeImages->url('about_bg')),
            'page' => [
                'title' => 'Syllabus',
                'subtitle' => 'Explore Syllabus for Every BNS Program',
                'label' => 'Syllabus',
                'intro' => [
                    'Select a program below to view its complete syllabus and learning structure.',
                ],
            ],
        ]);
    }
}
