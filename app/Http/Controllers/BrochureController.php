<?php

namespace App\Http\Controllers;

use App\Services\SiteSettingsService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BrochureController extends Controller
{
    public function __construct(private SiteSettingsService $settings) {}

    public function index(): View
    {
        return view('brochure.index', [
            'brochure' => $this->settings->brochureMeta(),
            'heroImage' => bns_vasset('assets/images/backgrounds/page-header-bg.jpg'),
        ]);
    }

    public function view(): BinaryFileResponse|Response
    {
        return $this->serveBrochure(inline: true);
    }

    public function download(): BinaryFileResponse|Response
    {
        return $this->serveBrochure(inline: false);
    }

    private function serveBrochure(bool $inline): BinaryFileResponse|Response
    {
        $brochure = $this->settings->brochureMeta();

        if (! $brochure['has_pdf'] || empty($brochure['path'])) {
            abort(404);
        }

        $fullPath = public_path($brochure['path']);

        if (! File::exists($fullPath)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        if ($inline) {
            $headers['Content-Disposition'] = 'inline; filename="BNS-Program-Brochure.pdf"';

            return response()->file($fullPath, $headers);
        }

        return response()->download($fullPath, 'BNS-Program-Brochure.pdf', $headers);
    }
}
