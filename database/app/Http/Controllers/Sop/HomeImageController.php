<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\HomePageImage;
use App\Services\HomeImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeImageController extends Controller
{
    public function __construct(private HomeImageService $homeImages) {}

    public function index(): View
    {
        $this->homeImages->syncDefinitionsFromConfig();

        return view('sop.home-images.index', [
            'sections' => $this->homeImages->groupedForAdmin(),
        ]);
    }

    public function update(Request $request, HomePageImage $homeImage): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:8192'],
        ]);

        $this->homeImages->storeUpload($homeImage, $request->file('image'));

        return back()->with('status', "{$homeImage->label} updated successfully.");
    }

    public function destroy(HomePageImage $homeImage): RedirectResponse
    {
        $this->homeImages->resetToDefault($homeImage);

        return back()->with('status', "{$homeImage->label} reset to default image.");
    }
}
