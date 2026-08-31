<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteBrandingController extends Controller
{
    public function __construct(private SiteSettingsService $settings) {}

    public function edit(): View
    {
        return view('sop.site-branding.edit', [
            'logoUrl' => $this->settings->logoUrl(),
            'faviconUrl' => $this->settings->faviconUrl(),
            'logoAlt' => $this->settings->logoAlt(),
            'hasCustomLogo' => $this->settings->hasCustomLogo(),
            'hasCustomFavicon' => $this->settings->hasCustomFavicon(),
            'header' => $this->settings->headerFormValues(),
            'headerPreview' => $this->settings->headerBar(),
            'brochure' => $this->settings->brochureMeta(),
            'brochureForm' => $this->settings->brochureFormValues(),
            'legalDatesForm' => $this->settings->legalFormValues(),
            'legalDatesPreview' => $this->settings->legalDates(),
            'heroVideoForm' => $this->settings->heroVideoFormValues(),
            'heroVideoPreview' => $this->settings->heroVideo(),
            'autoPurgeMobiles' => $this->settings->autoPurgeMobiles(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'logo_alt' => ['nullable', 'string', 'max:120'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,svg', 'max:4096'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,ico,svg', 'max:2048'],
            'header_email' => ['nullable', 'email', 'max:120'],
            'header_phone' => ['nullable', 'string', 'max:40'],
            'header_address' => ['nullable', 'string', 'max:160'],
            'header_welcome_text' => ['nullable', 'string', 'max:200'],
            'header_social_title' => ['nullable', 'string', 'max:60'],
            'header_social_twitter' => ['nullable', 'string', 'max:255'],
            'header_social_facebook' => ['nullable', 'string', 'max:255'],
            'header_social_pinterest' => ['nullable', 'string', 'max:255'],
            'header_social_instagram' => ['nullable', 'string', 'max:255'],
            'brochure_title' => ['nullable', 'string', 'max:120'],
            'brochure_subtitle' => ['nullable', 'string', 'max:160'],
            'brochure_intro' => ['nullable', 'string', 'max:500'],
            'brochure_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'legal_effective_date' => ['nullable', 'string', 'max:80'],
            'legal_last_updated' => ['nullable', 'string', 'max:80'],
            'hero_video_url' => ['nullable', 'url', 'max:500'],
            'hero_video_label' => ['nullable', 'string', 'max:80'],
            'auto_purge_mobiles' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->hasFile('logo')) {
            $this->settings->storeLogo($request->file('logo'));
        }

        if ($request->hasFile('favicon')) {
            $this->settings->storeFavicon($request->file('favicon'));
        }

        if ($request->hasFile('brochure_pdf')) {
            $this->settings->storeBrochure($request->file('brochure_pdf'));
        }

        $this->settings->set(
            SiteSettingsService::KEY_LOGO_ALT,
            trim((string) $request->input('logo_alt', 'BNS School')) ?: 'BNS School'
        );

        $this->settings->updateHeader([
            'email' => $request->input('header_email'),
            'phone' => $request->input('header_phone'),
            'address' => $request->input('header_address'),
            'welcome_text' => $request->input('header_welcome_text'),
            'social_title' => $request->input('header_social_title'),
            'social_twitter' => $request->input('header_social_twitter'),
            'social_facebook' => $request->input('header_social_facebook'),
            'social_pinterest' => $request->input('header_social_pinterest'),
            'social_instagram' => $request->input('header_social_instagram'),
        ]);

        $this->settings->updateBrochureMeta([
            'title' => $request->input('brochure_title'),
            'subtitle' => $request->input('brochure_subtitle'),
            'intro' => $request->input('brochure_intro'),
        ]);

        $this->settings->updateLegalDates([
            'effective_date' => $request->input('legal_effective_date'),
            'last_updated' => $request->input('legal_last_updated'),
        ]);

        $this->settings->updateHeroVideo([
            'url' => $request->input('hero_video_url'),
            'label' => $request->input('hero_video_label'),
        ]);

        $this->settings->updateAutoPurgeMobiles($request->input('auto_purge_mobiles'));

        return back()->with('status', 'Site settings updated successfully.');
    }

    public function destroyLogo(): RedirectResponse
    {
        $this->settings->resetLogo();

        return back()->with('status', 'Site logo reset to default.');
    }

    public function destroyFavicon(): RedirectResponse
    {
        $this->settings->resetFavicon();

        return back()->with('status', 'Favicon reset to default.');
    }

    public function destroyBrochure(): RedirectResponse
    {
        $this->settings->resetBrochure();

        return back()->with('status', 'Brochure PDF removed.');
    }
}
