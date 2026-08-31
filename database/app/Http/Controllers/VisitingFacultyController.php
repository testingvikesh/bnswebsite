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
        if (! Schema::hasTable('visiting_expert_faculty')) {
            return collect();
        }

        return VisitingExpertFaculty::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('full_name')
            ->get();
    }
}
