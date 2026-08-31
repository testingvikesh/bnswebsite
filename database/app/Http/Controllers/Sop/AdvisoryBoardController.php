<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AdvisoryBoardMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvisoryBoardController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();

        $members = AdvisoryBoardMember::query()
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('organization', 'like', "%{$search}%")
                        ->orWhere('expertise', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('full_name')
            ->paginate(12)
            ->withQueryString();

        return view('sop.advisory-board.index', compact('members', 'search'));
    }

    public function create(): View
    {
        return view('sop.advisory-board.create', [
            'member' => new AdvisoryBoardMember([
                'is_active' => true,
                'sort_order' => (AdvisoryBoardMember::max('sort_order') ?? 0) + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('advisory-board/photos', 'public');
        }

        AdvisoryBoardMember::create($validated);

        return redirect()->route('controlpanel.advisory-board.index')
            ->with('status', 'Advisory board member added successfully.');
    }

    public function edit(AdvisoryBoardMember $advisory_board): View
    {
        return view('sop.advisory-board.edit', ['member' => $advisory_board]);
    }

    public function update(Request $request, AdvisoryBoardMember $advisory_board): RedirectResponse
    {
        $validated = $this->validated($request, $advisory_board);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->boolean('remove_photo') && $advisory_board->photo_path) {
            $advisory_board->deletePhoto();
            $validated['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            $advisory_board->deletePhoto();
            $validated['photo_path'] = $request->file('photo')->store('advisory-board/photos', 'public');
        }

        $advisory_board->update($validated);

        return redirect()->route('controlpanel.advisory-board.index')
            ->with('status', 'Advisory board member updated successfully.');
    }

    public function destroy(AdvisoryBoardMember $advisory_board): RedirectResponse
    {
        $advisory_board->delete();

        return redirect()->route('controlpanel.advisory-board.index')
            ->with('status', 'Advisory board member deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?AdvisoryBoardMember $member = null): array
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'expertise' => ['required', 'string', 'max:500'],
            'profile' => ['required', 'string', 'max:1000'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ];

        return $request->validate($rules);
    }
}
