<?php

namespace App\Http\Controllers;

use App\Services\WhatsappPageService;
use Illuminate\View\View;

class WhatsappController extends Controller
{
    public function __construct(private WhatsappPageService $whatsappPage) {}

    public function index(): View
    {
        $page = $this->whatsappPage->get();

        return view('whatsapp.index', [
            'page' => $page,
            'heroImage' => $page->heroImageUrl(),
        ]);
    }
}
