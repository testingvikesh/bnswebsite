<?php

namespace App\Http\Controllers;

use App\Models\VisitingExpertFaculty;
use App\Services\FacultyPageService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class VisitingFacultyController extends Controller
{
    public function __construct(private FacultyPageService $facultyPage) {}

    public function index(): View
    {
        $page = $this->facultyPage->get();

        return view('about.faculty', [
            'page' => $page,
            'heroImage' => $page->heroImageUrl(),
            'facultyMembers' => $this->facultyMembers(),
        ]);
    }

    private function facultyMembers(): Collection
    {
        $featured = $this->featuredFaculty();
        $featuredNames = $featured
            ->map(fn (VisitingExpertFaculty $member) => $this->normalizeFacultyName((string) $member->full_name))
            ->filter()
            ->all();

        $fromDatabase = $this->databaseFaculty()
            ->reject(function (VisitingExpertFaculty $member) use ($featuredNames) {
                return in_array($this->normalizeFacultyName((string) $member->full_name), $featuredNames, true);
            });

        return $featured->concat($fromDatabase)->values();
    }

    /**
     * @return Collection<int, VisitingExpertFaculty>
     */
    private function featuredFaculty(): Collection
    {
        return collect(config('faculty.featured_profiles', []))
            ->filter(fn ($row) => is_array($row) && filled($row['full_name'] ?? null))
            ->map(function (array $row) {
                $member = new VisitingExpertFaculty();
                $member->forceFill([
                    'title_prefix' => $row['title_prefix'] ?? 'Mr.',
                    'full_name' => $row['full_name'],
                    'photo_path' => $row['photo_path'] ?? null,
                    'designation' => $row['designation'] ?? config('faculty.default_designation'),
                    'recognition' => $row['recognition'] ?? null,
                    'expertise' => $row['expertise'] ?? '',
                    'professional_experience' => $row['professional_experience'] ?? null,
                    'industry' => $row['industry'] ?? null,
                    'qualification' => $row['qualification'] ?? null,
                    'specialization' => $row['specialization'] ?? null,
                    'about' => $row['about'] ?? null,
                    'sort_order' => (int) ($row['sort_order'] ?? 0),
                    'is_active' => true,
                ]);
                $member->setAttribute('current_role', $row['current_role'] ?? null);
                $member->setAttribute('secondary_role', $row['secondary_role'] ?? null);
                $member->setAttribute('industry_exposure', $row['industry_exposure'] ?? null);
                $member->setAttribute('experience_type', $row['experience_type'] ?? null);
                $member->setAttribute('expertise_summary', $row['expertise_summary'] ?? null);
                $member->setAttribute('digital_experience', $row['digital_experience'] ?? null);
                $member->setAttribute('digital_experience_label', $row['digital_experience_label'] ?? 'Digital Marketing');
                $member->setAttribute('profile_facts', $row['profile_facts'] ?? []);
                $member->setAttribute('expertise_section_title', $row['expertise_section_title'] ?? 'Core Expertise');
                $member->setAttribute('experience_section_title', $row['experience_section_title'] ?? 'Professional Experience');
                $member->setAttribute('achievement_section_title', $row['achievement_section_title'] ?? 'Major Achievement');
                $member->setAttribute('experience_points', $row['experience_points'] ?? []);
                $member->setAttribute('achievement_points', $row['achievement_points'] ?? []);
                $member->setAttribute('current_focus', $row['current_focus'] ?? null);
                $member->setAttribute('coach_intro', $row['coach_intro'] ?? null);
                $member->setAttribute('coach_points', $row['coach_points'] ?? []);
                $member->setAttribute('coach_outcome', $row['coach_outcome'] ?? null);
                $member->setAttribute('taglines', $row['taglines'] ?? []);

                return $member;
            })
            ->values();
    }

    private function normalizeFacultyName(string $name): string
    {
        $name = mb_strtolower(trim($name));
        $name = str_replace(["\u{2019}", "\u{2018}", '`'], "'", $name);
        $name = str_replace("'", '', $name);

        return preg_replace('/\s+/', ' ', $name) ?? $name;
    }

    /**
     * @return Collection<int, VisitingExpertFaculty>
     */
    private function databaseFaculty(): Collection
    {
        try {
            if (! Schema::hasTable('visiting_expert_faculty')) {
                return collect();
            }

            $query = VisitingExpertFaculty::query()
                ->where('is_active', true);

            foreach ((array) config('faculty.hidden_full_names', []) as $hiddenName) {
                $hiddenName = trim((string) $hiddenName);
                if ($hiddenName === '') {
                    continue;
                }

                $query->where('full_name', 'not like', '%'.$hiddenName.'%');
            }

            return $query
                ->orderBy('sort_order')
                ->orderBy('full_name')
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }
}
