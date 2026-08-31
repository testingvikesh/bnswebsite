<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\VisitingExpertFaculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VisitingExpertFacultyController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();

        $faculty = VisitingExpertFaculty::query()
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('recognition', 'like', "%{$search}%")
                        ->orWhere('industry', 'like', "%{$search}%")
                        ->orWhere('qualification', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('full_name')
            ->paginate(12)
            ->withQueryString();

        return view('sop.visiting-faculty.index', compact('faculty', 'search'));
    }

    public function create(): View
    {
        return view('sop.visiting-faculty.create', [
            'faculty' => new VisitingExpertFaculty([
                'is_active' => true,
                'designation' => config('faculty.default_designation'),
                'recognition' => config('faculty.default_recognition'),
                'sort_order' => (VisitingExpertFaculty::max('sort_order') ?? 0) + 1,
            ]),
            'titlePrefixes' => config('faculty.title_prefixes'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('visiting-faculty/photos', 'public');
        }

        VisitingExpertFaculty::create($validated);

        return redirect()->route('controlpanel.visiting-faculty.index')
            ->with('status', 'Visiting expert faculty profile added successfully.');
    }

    public function edit(VisitingExpertFaculty $visiting_faculty): View
    {
        return view('sop.visiting-faculty.edit', [
            'faculty' => $visiting_faculty,
            'titlePrefixes' => config('faculty.title_prefixes'),
        ]);
    }

    public function update(Request $request, VisitingExpertFaculty $visiting_faculty): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->boolean('remove_photo') && $visiting_faculty->photo_path) {
            $visiting_faculty->deletePhoto();
            $validated['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            $visiting_faculty->deletePhoto();
            $validated['photo_path'] = $request->file('photo')->store('visiting-faculty/photos', 'public');
        }

        $visiting_faculty->update($validated);

        return redirect()->route('controlpanel.visiting-faculty.index')
            ->with('status', 'Faculty profile updated successfully.');
    }

    public function destroy(VisitingExpertFaculty $visiting_faculty): RedirectResponse
    {
        $visiting_faculty->delete();

        return redirect()->route('controlpanel.visiting-faculty.index')
            ->with('status', 'Faculty profile deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title_prefix' => ['nullable', 'string', 'max:20', Rule::in(config('faculty.title_prefixes'))],
            'full_name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'recognition' => ['nullable', 'string', 'max:500'],
            'expertise' => ['required', 'string', 'max:1000'],
            'professional_experience' => ['nullable', 'string', 'max:100'],
            'industry' => ['nullable', 'string', 'max:500'],
            'qualification' => ['nullable', 'string', 'max:500'],
            'specialization' => ['nullable', 'string', 'max:500'],
            'faculty_since' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'sessions_conducted' => ['nullable', 'string', 'max:50'],
            'learners_mentored' => ['nullable', 'string', 'max:50'],
            'languages' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        if (empty($validated['title_prefix'])) {
            $validated['title_prefix'] = null;
        }

        return $validated;
    }
}
