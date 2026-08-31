<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();
        $category = $request->string('category')->toString();

        $members = TeamMember::query()
            ->when($category !== '', fn ($q) => $q->where('category', $category))
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%");
                });
            })
            ->orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('full_name')
            ->paginate(12)
            ->withQueryString();

        return view('sop.team-members.index', compact('members', 'search', 'category'));
    }

    public function create(Request $request): View
    {
        $category = $request->string('category')->toString() ?: TeamMember::CATEGORY_LEADERSHIP;

        return view('sop.team-members.create', [
            'member' => new TeamMember([
                'category' => $category,
                'is_active' => true,
                'sort_order' => (TeamMember::max('sort_order') ?? 0) + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['expertise'] = $this->parseExpertise($request->input('expertise', ''));

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('team-members/photos', 'public');
        }

        TeamMember::create($validated);

        return redirect()->route('controlpanel.team-members.index')
            ->with('status', 'Team member added successfully.');
    }

    public function edit(TeamMember $team_member): View
    {
        return view('sop.team-members.edit', ['member' => $team_member]);
    }

    public function update(Request $request, TeamMember $team_member): RedirectResponse
    {
        $validated = $this->validated($request, $team_member);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['expertise'] = $this->parseExpertise($request->input('expertise', ''));

        if ($request->boolean('remove_photo') && $team_member->photo_path) {
            $team_member->deletePhoto();
            $validated['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            $team_member->deletePhoto();
            $validated['photo_path'] = $request->file('photo')->store('team-members/photos', 'public');
        }

        $team_member->update($validated);

        return redirect()->route('controlpanel.team-members.index')
            ->with('status', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $team_member): RedirectResponse
    {
        $team_member->delete();

        return redirect()->route('controlpanel.team-members.index')
            ->with('status', 'Team member deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?TeamMember $member = null): array
    {
        return $request->validate([
            'category' => ['required', 'in:leadership,academic'],
            'full_name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:500'],
            'expertise' => ['nullable', 'string', 'max:1000'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);
    }

    /** @return list<string> */
    private function parseExpertise(string $input): array
    {
        return collect(preg_split('/[,|]/', $input))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
