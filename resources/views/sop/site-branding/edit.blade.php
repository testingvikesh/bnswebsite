@extends('sop.layouts.app')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')

@push('styles')
<style>
    .bns-brand-preview {
        background: #f1f5f9;
        border: 1px solid var(--bns-border);
        border-radius: 12px;
        padding: 1.5rem;
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bns-brand-preview img {
        max-width: 100%;
        max-height: 100px;
        object-fit: contain;
    }
    .bns-brand-preview--favicon img {
        max-height: 48px;
    }
    .bns-brand-preview--dark {
        background: linear-gradient(135deg, #0a2240 0%, #123a5e 100%);
    }
    .bns-header-preview {
        background: var(--eduvers-base, #fe5500);
        color: #fff;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.88rem;
    }
    .bns-header-preview__row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .bns-header-preview__contacts {
        display: flex;
        flex-wrap: wrap;
        gap: 14px 18px;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .bns-header-preview__contacts li {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .bns-header-preview__social {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .bns-header-preview__social a {
        color: #fff;
        opacity: 0.95;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <p class="text-muted mb-0">
        Manage site logo, favicon, and the orange top header bar shown on every public page.
    </p>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="bns-card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-image me-2 text-danger"></i>Site Logo</h5>
            <div class="bns-brand-preview bns-brand-preview--dark mb-3">
                <img src="{{ $logoUrl }}" alt="{{ $logoAlt }}">
            </div>
            @if ($hasCustomLogo)
                <span class="badge text-bg-success mb-3">Custom logo</span>
                <form method="POST" action="{{ route('controlpanel.site-branding.destroy-logo') }}" onsubmit="return confirm('Reset logo to default?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Reset logo</button>
                </form>
            @else
                <span class="badge text-bg-secondary mb-3">Default logo</span>
            @endif
        </div>
    </div>
    <div class="col-lg-6">
        <div class="bns-card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-bookmark me-2 text-danger"></i>Favicon</h5>
            <div class="bns-brand-preview mb-3">
                <img src="{{ $faviconUrl }}" alt="Favicon">
            </div>
            @if ($hasCustomFavicon)
                <span class="badge text-bg-success mb-3">Custom favicon</span>
                <form method="POST" action="{{ route('controlpanel.site-branding.destroy-favicon') }}" onsubmit="return confirm('Reset favicon to default?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Reset favicon</button>
                </form>
            @else
                <span class="badge text-bg-secondary mb-3">Default favicon</span>
            @endif
        </div>
    </div>
</div>

<form method="POST" action="{{ route('controlpanel.site-branding.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="bns-card p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-layout-text-window-reverse me-2 text-danger"></i>Top Header Bar</h5>
        <div class="bns-header-preview mb-4">
            <div class="bns-header-preview__row">
                <ul class="bns-header-preview__contacts">
                    @if($headerPreview['email'])<li><i class="fal fa-envelope"></i> {{ $headerPreview['email'] }}</li>@endif
                    @if($headerPreview['phone'])<li><i class="far fa-phone"></i> {{ $headerPreview['phone'] }}</li>@endif
                    @if($headerPreview['address'])<li><i class="far fa-map-marker-alt"></i> {{ $headerPreview['address'] }}</li>@endif
                </ul>
                @if(!empty($headerPreview['social_links']))
                <div class="bns-header-preview__social">
                    <span>{{ $headerPreview['social_title'] }}</span>
                    @foreach($headerPreview['social_links'] as $social)
                        <a href="{{ $social['url'] }}" tabindex="-1"><i class="{{ $social['icon'] }}"></i></a>
                    @endforeach
                </div>
                @endif
            </div>
            @if($headerPreview['welcome_text'])
                <p class="mb-0 mt-2 small opacity-90">{{ $headerPreview['welcome_text'] }}</p>
            @endif
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="header_email" class="form-control" value="{{ old('header_email', $header['email']) }}" maxlength="120">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Phone</label>
                <input type="text" name="header_phone" class="form-control" value="{{ old('header_phone', $header['phone']) }}" maxlength="40" placeholder="+91 98250 98250">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Address</label>
                <input type="text" name="header_address" class="form-control" value="{{ old('header_address', $header['address']) }}" maxlength="160">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Welcome text (center)</label>
                <input type="text" name="header_welcome_text" class="form-control" value="{{ old('header_welcome_text', $header['welcome_text']) }}" maxlength="200">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Social section label</label>
                <input type="text" name="header_social_title" class="form-control" value="{{ old('header_social_title', $header['social_title']) }}" maxlength="60">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Twitter / X URL</label>
                <input type="text" name="header_social_twitter" class="form-control" value="{{ old('header_social_twitter', $header['social_twitter']) }}" placeholder="https://twitter.com/..." inputmode="url">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Facebook URL</label>
                <input type="text" name="header_social_facebook" class="form-control" value="{{ old('header_social_facebook', $header['social_facebook']) }}" placeholder="https://facebook.com/..." inputmode="url">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Pinterest URL</label>
                <input type="text" name="header_social_pinterest" class="form-control" value="{{ old('header_social_pinterest', $header['social_pinterest']) }}" placeholder="https://pinterest.com/..." inputmode="url">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Instagram URL</label>
                <input type="text" name="header_social_instagram" class="form-control" value="{{ old('header_social_instagram', $header['social_instagram']) }}" placeholder="https://instagram.com/..." inputmode="url">
            </div>
            <div class="col-12">
                <div class="form-text">Leave a social URL empty to hide that icon. Defaults come from <code>config/site.php</code> until you save custom values.</div>
            </div>
        </div>
    </div>

    <div class="bns-card p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-text me-2 text-danger"></i>Legal Policy Dates</h5>
        <p class="text-muted small mb-3">Shown on all legal pages (Privacy Policy, Terms, Refund, Cookie, Disclaimer, etc.). Leave empty to use the default fallback text.</p>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Effective date</label>
                <input type="date" name="legal_effective_date" class="form-control" value="{{ old('legal_effective_date', $legalDatesForm['effective_date']) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Last updated</label>
                <input type="date" name="legal_last_updated" class="form-control" value="{{ old('legal_last_updated', $legalDatesForm['last_updated']) }}">
            </div>
            <div class="col-12">
                <div class="form-text">Preview: Effective Date — <strong>{{ $legalDatesPreview['effective_date'] }}</strong> | Last Updated — <strong>{{ $legalDatesPreview['last_updated'] }}</strong></div>
            </div>
        </div>
    </div>

    <div class="bns-card p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-youtube me-2 text-danger"></i>Homepage Introduction Video</h5>
        <p class="text-muted small mb-3">YouTube link for the <strong>Introduction Video</strong> button on the homepage hero. Leave blank to hide the button.</p>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">YouTube URL</label>
                <input type="text" name="hero_video_url" class="form-control" value="{{ old('hero_video_url', $heroVideoForm['url']) }}" placeholder="https://www.youtube.com/watch?v=..." maxlength="500" inputmode="url">
                <div class="form-text">Full YouTube watch or youtu.be link.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Button label</label>
                <input type="text" name="hero_video_label" class="form-control" value="{{ old('hero_video_label', $heroVideoForm['label']) }}" maxlength="80">
            </div>
            <div class="col-12">
                @if($heroVideoPreview['has_video'])
                    <div class="form-text">Preview: <a href="{{ $heroVideoPreview['url'] }}" target="_blank" rel="noopener">{{ $heroVideoPreview['label'] }}</a></div>
                @else
                    <div class="form-text text-warning">No video URL set — the homepage video button will be hidden.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="bns-card p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-phone me-2 text-danger"></i>Auto-purge Test Mobiles</h5>
        <p class="text-muted small mb-3">
            Enter comma-separated mobile numbers used for testing.
            Any new registration with these numbers is <strong>auto-deleted after 5 minutes</strong>
            (including attendance, QR invites, related payments, and membership uploads).
        </p>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Test mobile numbers</label>
                <textarea name="auto_purge_mobiles" class="form-control" rows="3" maxlength="500"
                          placeholder="9876543210, 9123456789">{{ old('auto_purge_mobiles', $autoPurgeMobiles) }}</textarea>
                <div class="form-text">Example: <code>9876543210, 9123456789</code>. Leave empty to disable auto-purge.</div>
            </div>
        </div>
    </div>

    <div class="bns-card p-4">
        <h5 class="fw-bold mb-3">Logo &amp; favicon upload</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Site logo</label>
                <input type="file" name="logo" class="form-control" accept="image/*,.svg">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Favicon</label>
                <input type="file" name="favicon" class="form-control" accept="image/*,.ico,.svg">
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Logo alt text</label>
                <input type="text" name="logo_alt" class="form-control" value="{{ old('logo_alt', $logoAlt) }}" maxlength="120">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-check-lg me-1"></i> Save all settings
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
