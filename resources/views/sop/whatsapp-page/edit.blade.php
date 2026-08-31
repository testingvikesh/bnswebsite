@extends('sop.layouts.app')

@section('title', 'WhatsApp Support Page')
@section('page-title', 'WhatsApp Support Page')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Edit <a href="{{ route('whatsapp.support') }}" target="_blank">WhatsApp Support</a> page content and chat links.</p>
    </div>
    <a href="{{ route('whatsapp.support') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> Preview</a>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@php
    $quickLines = collect($page->quick_options ?? [])->map(fn ($o) => ($o['icon'] ?? '').'|'.($o['label'] ?? '').'|'.($o['message'] ?? ''))->implode("\n");
    $tapLines = collect($page->one_tap_actions ?? [])->map(function ($a) {
        $type = $a['type'] ?? 'whatsapp';
        $extra = $type === 'whatsapp' ? ($a['message'] ?? '') : ($a['url'] ?? '');
        return ($a['label'] ?? '').'|'.$type.'|'.$extra;
    })->implode("\n");
@endphp

<form method="POST" action="{{ route('controlpanel.whatsapp-page.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Page header</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Title</label><input type="text" name="page_title" class="form-control" value="{{ old('page_title', $page->page_title) }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Subtitle</label><input type="text" name="page_subtitle" class="form-control" value="{{ old('page_subtitle', $page->page_subtitle) }}"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Introduction</label><textarea name="page_intro" rows="3" class="form-control" required>{{ old('page_intro', $page->page_intro) }}</textarea></div>
                    <div class="col-12"><label class="form-label fw-semibold">Introduction (paragraph 2)</label><textarea name="page_intro_2" rows="2" class="form-control">{{ old('page_intro_2', $page->page_intro_2) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">How we help</h5>
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Section title</label><input type="text" name="help_title" class="form-control" value="{{ old('help_title', $page->help_title) }}"></div>
                    <div class="col-12"><label class="form-label">Intro</label><textarea name="help_intro" rows="2" class="form-control">{{ old('help_intro', $page->help_intro) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Help items (one per line)</label><textarea name="help_items_text" rows="8" class="form-control">{{ old('help_items_text', implode("\n", $page->help_items ?? [])) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Chat with us</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Section title</label><input type="text" name="chat_title" class="form-control" value="{{ old('chat_title', $page->chat_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">WhatsApp number</label><input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $page->whatsapp_number) }}" placeholder="+91 98250 98250"></div>
                    <div class="col-md-6"><label class="form-label">Availability label</label><input type="text" name="availability_label" class="form-control" value="{{ old('availability_label', $page->availability_label) }}"></div>
                    <div class="col-md-6"><label class="form-label">Availability hours (one per line)</label><textarea name="availability_hours_text" rows="2" class="form-control">{{ old('availability_hours_text', implode("\n", $page->availability_hours ?? [])) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Quick WhatsApp options</h5>
                <p class="text-muted small">One per line: <code>Icon|Label|Pre-filled message</code></p>
                <textarea name="quick_options_text" rows="12" class="form-control font-monospace">{{ old('quick_options_text', $quickLines) }}</textarea>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Before you chat</h5>
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Title</label><input type="text" name="before_chat_title" class="form-control" value="{{ old('before_chat_title', $page->before_chat_title) }}"></div>
                    <div class="col-12"><label class="form-label">Intro</label><textarea name="before_chat_intro" rows="2" class="form-control">{{ old('before_chat_intro', $page->before_chat_intro) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Items (one per line)</label><textarea name="before_chat_items_text" rows="5" class="form-control">{{ old('before_chat_items_text', implode("\n", $page->before_chat_items ?? [])) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">One-tap actions</h5>
                <p class="text-muted small">One per line: <code>Label|whatsapp|Message</code> or <code>Label|url|/register</code></p>
                <textarea name="one_tap_actions_text" rows="6" class="form-control font-monospace">{{ old('one_tap_actions_text', $tapLines) }}</textarea>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Immediate assistance &amp; tagline</h5>
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Section title</label><input type="text" name="immediate_title" class="form-control" value="{{ old('immediate_title', $page->immediate_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="immediate_phone" class="form-control" value="{{ old('immediate_phone', $page->immediate_phone) }}"></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="immediate_email" class="form-control" value="{{ old('immediate_email', $page->immediate_email) }}"></div>
                    <div class="col-md-6"><label class="form-label">Website</label><input type="text" name="immediate_website" class="form-control" value="{{ old('immediate_website', $page->immediate_website) }}"></div>
                    <div class="col-md-6"><label class="form-label">Nearest centre URL</label><input type="text" name="immediate_centre_url" class="form-control" value="{{ old('immediate_centre_url', $page->immediate_centre_url) }}"></div>
                    <div class="col-md-6"><label class="form-label">Tagline brand</label><input type="text" name="tagline_brand" class="form-control" value="{{ old('tagline_brand', $page->tagline_brand) }}"></div>
                    <div class="col-md-6"><label class="form-label">Tagline text</label><input type="text" name="tagline_text" class="form-control" value="{{ old('tagline_text', $page->tagline_text) }}"></div>
                    <div class="col-md-6"><label class="form-label">Tagline subtext</label><input type="text" name="tagline_subtext" class="form-control" value="{{ old('tagline_subtext', $page->tagline_subtext) }}"></div>
                    <div class="col-md-6"><label class="form-label">Hindi tagline</label><input type="text" name="tagline_hindi" class="form-control" value="{{ old('tagline_hindi', $page->tagline_hindi) }}"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Hero image</h5>
                @if($page->hero_image)
                    <img src="{{ asset($page->hero_image) }}" alt="" class="img-fluid rounded mb-3">
                @endif
                <input type="file" name="hero_image" class="form-control mb-2" accept="image/*">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remove_hero_image" value="1" id="remove_hero_image">
                    <label class="form-check-label" for="remove_hero_image">Remove current image</label>
                </div>
            </div>
            <div class="bns-card p-4 mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Page active</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </div>
    </div>
</form>
@endsection
