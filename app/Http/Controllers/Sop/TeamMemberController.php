<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
        $validated = $this->applyMemberContent($validated, $request);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $this->storePhoto($request->file('photo'));
        }

        TeamMember::create($validated);

        return redirect()->route('controlpanel.team-members.index')
            ->with('status', 'Team member added successfully.');
    }

    public function edit(TeamMember $team_member): View
    {
        $team_member->migratePhotoToPublicUploads();
        $team_member->refresh();

        return view('sop.team-members.edit', ['member' => $team_member]);
    }

    public function update(Request $request, TeamMember $team_member): RedirectResponse
    {
        $team_member->migratePhotoToPublicUploads();
        $team_member->refresh();

        $validated = $this->validated($request, $team_member);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated = $this->applyMemberContent($validated, $request);

        if ($request->boolean('remove_photo') && $team_member->photo_path) {
            $team_member->deletePhoto();
            $validated['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            $team_member->deletePhoto();
            $validated['photo_path'] = $this->storePhoto($request->file('photo'));
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
            'profile' => ['nullable', 'string', 'max:20000'],
            'expertise' => ['nullable', 'string', 'max:12000'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);
    }

    /** @param array<string, mixed> $validated */
    private function applyMemberContent(array $validated, Request $request): array
    {
        if (($validated['category'] ?? '') === TeamMember::CATEGORY_LEADERSHIP) {
            $validated['profile'] = trim((string) $request->input('profile', '')) ?: null;
            $validated['expertise'] = [];
        } else {
            $validated['profile'] = null;
            $validated['expertise'] = $this->parseExpertise($request->input('expertise', ''));
        }

        return $validated;
    }

    /** @return list<string> */
    private function parseExpertise(string $input): array
    {
        $input = trim($input);

        if ($input === '') {
            return [];
        }

        if (str_contains($input, '<')) {
            $items = [];

            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $input, $matches)) {
                foreach ($matches[1] as $match) {
                    $text = $this->plainTextFromHtml($match);
                    if ($text !== '') {
                        $items[] = $text;
                    }
                }
            }

            if ($items === [] && preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $input, $matches)) {
                foreach ($matches[1] as $match) {
                    $text = $this->plainTextFromHtml($match);
                    if ($text !== '') {
                        $items[] = $text;
                    }
                }
            }

            if ($items !== []) {
                return $items;
            }

            $fallback = $this->plainTextFromHtml($input);

            if ($fallback !== '') {
                return collect(preg_split('/\R+/', $fallback))
                    ->map(fn (string $item) => trim($item))
                    ->filter()
                    ->values()
                    ->all();
            }

            return [];
        }

        return collect(preg_split('/\R+|,(?=\s)/', $input))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    private function plainTextFromHtml(string $html): string
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim((string) $text);
    }

    private function storePhoto($file): string
    {
        $directory = public_path('uploads/team/members');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'member-'.time().'-'.Str::lower(Str::random(6)).'.'.strtolower($extension);
        $file->move($directory, $filename);

        return 'uploads/team/members/'.$filename;
    }
}
