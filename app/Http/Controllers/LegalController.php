<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LegalController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('legal.show', config('legal.default', 'privacy-policy'));
    }

    public function show(string $slug): View
    {
        $policies = config('legal.policies', []);

        if (! isset($policies[$slug])) {
            abort(404);
        }

        return view('legal.show', [
            'slug' => $slug,
            'policies' => $policies,
            'policy' => $policies[$slug],
            'title' => $policies[$slug]['title'],
            'pageContent' => config("legal_sections.{$slug}"),
            'heroImage' => bns_vasset('assets/images/backgrounds/page-header-bg.jpg'),
        ]);
    }
}
