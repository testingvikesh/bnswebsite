<?php

namespace App\Http\Controllers;

use App\Services\SocialPageService;
use Illuminate\View\View;

class SocialController extends Controller
{
    public function __construct(private SocialPageService $socialPage) {}

    public function index(): View
    {
        $page = $this->socialPage->get();

        return view('social.index', [
            'page' => $page,
            'heroImage' => $page->heroImageUrl(),
        ]);
    }
}
