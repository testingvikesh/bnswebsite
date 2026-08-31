<?php

namespace App\Http\Controllers;

use App\Models\AdvisoryBoardMember;
use App\Models\TeamMember;
use App\Services\TeamPageService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct(private TeamPageService $teamPage) {}

    public function index(): View
    {
        $page = $this->teamPage->get();

        return view('about.team', [
            'page' => $page,
            'heroImage' => $page->heroImageUrl(),
            'leadershipMembers' => $this->teamPage->members(TeamMember::CATEGORY_LEADERSHIP),
            'academicMembers' => $this->teamPage->members(TeamMember::CATEGORY_ACADEMIC),
            'advisoryMembers' => $this->advisoryMembers(),
        ]);
    }

    private function advisoryMembers(): Collection
    {
        if (! Schema::hasTable('advisory_board_members')) {
            return collect();
        }

        return AdvisoryBoardMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('full_name')
            ->get();
    }
}
