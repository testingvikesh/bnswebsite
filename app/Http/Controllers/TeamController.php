<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Services\TeamPageService;
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
        ]);
    }
}
