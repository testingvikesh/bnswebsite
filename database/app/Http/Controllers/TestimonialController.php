<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('testimonials.index', [
            'testimonialsPage' => config('testimonials'),
            'testimonials' => $this->testimonials(),
        ]);
    }

    private function testimonials(): Collection
    {
        if (! Schema::hasTable('testimonials')) {
            return collect();
        }

        return Testimonial::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('full_name')
            ->get();
    }
}
