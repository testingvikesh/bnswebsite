<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();

        $testimonials = Testimonial::query()
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('organization', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('full_name')
            ->paginate(12)
            ->withQueryString();

        return view('sop.testimonials.index', compact('testimonials', 'search'));
    }

    public function create(): View
    {
        return view('sop.testimonials.create', [
            'testimonial' => new Testimonial([
                'is_active' => true,
                'sort_order' => (Testimonial::max('sort_order') ?? 0) + 1,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $this->storePhoto($request->file('photo'));
        }

        Testimonial::create($validated);

        return redirect()->route('controlpanel.testimonials.index')
            ->with('status', 'Testimonial added successfully.');
    }

    public function edit(Testimonial $testimonial): View
    {
        $testimonial->migratePhotoToPublicUploads();
        $testimonial->refresh();

        return view('sop.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->migratePhotoToPublicUploads();
        $testimonial->refresh();

        $validated = $this->validated($request);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->boolean('remove_photo') && $testimonial->photo_path) {
            $testimonial->deletePhoto();
            $validated['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            $testimonial->deletePhoto();
            $validated['photo_path'] = $this->storePhoto($request->file('photo'));
        }

        $testimonial->update($validated);

        return redirect()->route('controlpanel.testimonials.index')
            ->with('status', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('controlpanel.testimonials.index')
            ->with('status', 'Testimonial deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);
    }

    private function storePhoto($file): string
    {
        $directory = public_path('uploads/testimonials');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = 'testimonial-'.time().'-'.Str::lower(Str::random(6)).'.'.strtolower($extension);
        $file->move($directory, $filename);

        return 'uploads/testimonials/'.$filename;
    }
}
