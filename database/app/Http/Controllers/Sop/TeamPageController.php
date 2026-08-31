<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Services\TeamPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamPageController extends Controller
{
    public function __construct(private TeamPageService $teamPage) {}

    public function edit(): View
    {
        $page = $this->teamPage->get();

        if (! $page->exists) {
            $page = $this->teamPage->seedPage();
        }

        return view('sop.team-page.edit', ['page' => $page]);
    }

    public function update(Request $request): RedirectResponse
    {
        $page = $this->teamPage->get();

        if (! $page->exists) {
            $page = $this->teamPage->seedPage();
        }

        $validated = $request->validate([
            'page_title' => ['required', 'string', 'max:255'],
            'page_subtitle' => ['nullable', 'string', 'max:255'],
            'page_intro' => ['required', 'string', 'max:5000'],
            'leadership_title' => ['required', 'string', 'max:255'],
            'academic_title' => ['required', 'string', 'max:255'],
            'advisory_title' => ['required', 'string', 'max:255'],
            'collab_badge' => ['nullable', 'string', 'max:255'],
            'collab_title' => ['nullable', 'string', 'max:255'],
            'collab_description' => ['nullable', 'string', 'max:5000'],
            'operations_title' => ['required', 'string', 'max:255'],
            'ops_names' => ['nullable', 'array'],
            'ops_names.*' => ['nullable', 'string', 'max:255'],
            'ops_descriptions' => ['nullable', 'array'],
            'ops_descriptions.*' => ['nullable', 'string', 'max:500'],
            'ops_icons' => ['nullable', 'array'],
            'ops_icons.*' => ['nullable', 'string', 'max:100'],
            'values_title' => ['required', 'string', 'max:255'],
            'values_items' => ['nullable', 'array'],
            'values_items.*' => ['nullable', 'string', 'max:100'],
            'join_title' => ['required', 'string', 'max:255'],
            'join_intro' => ['nullable', 'string', 'max:2000'],
            'join_looking_label' => ['nullable', 'string', 'max:255'],
            'join_roles' => ['nullable', 'array'],
            'join_roles.*' => ['nullable', 'string', 'max:100'],
            'join_cta_title' => ['nullable', 'string', 'max:255'],
            'join_cta_text' => ['nullable', 'string', 'max:1000'],
            'join_contact_email' => ['nullable', 'email', 'max:255'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $operations = [];
        $names = $validated['ops_names'] ?? [];
        foreach ($names as $i => $name) {
            $name = trim((string) $name);
            $desc = trim((string) ($validated['ops_descriptions'][$i] ?? ''));
            $icon = trim((string) ($validated['ops_icons'][$i] ?? 'fas fa-users'));
            if ($name !== '') {
                $operations[] = ['name' => $name, 'description' => $desc, 'icon' => $icon ?: 'fas fa-users'];
            }
        }

        $values = collect($validated['values_items'] ?? [])
            ->map(fn ($v) => trim((string) $v))
            ->filter()
            ->values()
            ->all();

        $roles = collect($validated['join_roles'] ?? [])
            ->map(fn ($r) => trim((string) $r))
            ->filter()
            ->values()
            ->all();

        if ($request->boolean('remove_hero_image')) {
            $page->deleteHeroImage();
            $page->hero_image = null;
        }

        if ($request->hasFile('hero_image')) {
            $this->teamPage->storeHeroImage($page, $request->file('hero_image'));
        }

        $page->update([
            'page_title' => $validated['page_title'],
            'page_subtitle' => $validated['page_subtitle'],
            'page_intro' => $validated['page_intro'],
            'leadership_title' => $validated['leadership_title'],
            'academic_title' => $validated['academic_title'],
            'advisory_title' => $validated['advisory_title'],
            'collab_badge' => $validated['collab_badge'],
            'collab_title' => $validated['collab_title'],
            'collab_description' => $validated['collab_description'],
            'operations_title' => $validated['operations_title'],
            'operations_teams' => $operations,
            'values_title' => $validated['values_title'],
            'values_items' => $values,
            'join_title' => $validated['join_title'],
            'join_intro' => $validated['join_intro'],
            'join_looking_label' => $validated['join_looking_label'],
            'join_roles' => $roles,
            'join_cta_title' => $validated['join_cta_title'],
            'join_cta_text' => $validated['join_cta_text'],
            'join_contact_email' => $validated['join_contact_email'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->teamPage->clearCache();

        return redirect()->route('controlpanel.team-page.edit')
            ->with('status', 'Team page updated successfully.');
    }
}
