<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AdvisoryBoardMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\VisitingExpertFaculty;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $isAdmin = $user->isSopAdmin();

        return view('sop.dashboard.index', [
            'userCount' => $isAdmin ? User::count() : null,
            'advisoryCount' => $isAdmin ? $this->countIfTable('advisory_board_members', AdvisoryBoardMember::class) : null,
            'facultyCount' => $isAdmin ? $this->countIfTable('visiting_expert_faculty', VisitingExpertFaculty::class) : null,
            'testimonialCount' => $isAdmin ? $this->countIfTable('testimonials', Testimonial::class) : null,
        ]);
    }

    private function countIfTable(string $table, string $model): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        return $model::count();
    }
}
