@extends('sop.layouts.app')

@section('title', 'Contact Page')
@section('page-title', 'Contact Us Page')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Edit <a href="{{ route('contact') }}" target="_blank">Contact Us</a>. View form submissions in <a href="{{ route('controlpanel.contact-inquiries.index') }}">Contact Inquiries</a>.</p>
    </div>
    <a href="{{ route('contact') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> Preview</a>
</div>

<form method="POST" action="{{ route('controlpanel.contact-page.update') }}" enctype="multipart/form-data">
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
                <h5 class="fw-bold mb-3">Admission office</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Office title</label><input type="text" name="office_title" class="form-control" value="{{ old('office_title', $page->office_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">Brand name</label><input type="text" name="office_brand" class="form-control" value="{{ old('office_brand', $page->office_brand) }}"></div>
                    <div class="col-12"><label class="form-label">Tagline</label><input type="text" name="office_tagline" class="form-control" value="{{ old('office_tagline', $page->office_tagline) }}"></div>
                    <div class="col-md-6"><label class="form-label">Head office label</label><input type="text" name="office_head_label" class="form-control" value="{{ old('office_head_label', $page->office_head_label) }}"></div>
                    <div class="col-md-6"><label class="form-label">Address line</label><input type="text" name="address_line" class="form-control" value="{{ old('address_line', $page->address_line) }}"></div>
                    <div class="col-md-4"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $page->city) }}"></div>
                    <div class="col-md-4"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ old('state', $page->state) }}"></div>
                    <div class="col-md-4"><label class="form-label">PIN Code</label><input type="text" name="pin_code" class="form-control" value="{{ old('pin_code', $page->pin_code) }}"></div>
                    <div class="col-md-4"><label class="form-label">Helpline</label><input type="text" name="phone_helpline" class="form-control" value="{{ old('phone_helpline', $page->phone_helpline) }}"></div>
                    <div class="col-md-4"><label class="form-label">WhatsApp</label><input type="text" name="phone_whatsapp" class="form-control" value="{{ old('phone_whatsapp', $page->phone_whatsapp) }}"></div>
                    <div class="col-md-4"><label class="form-label">Office phone</label><input type="text" name="phone_office" class="form-control" value="{{ old('phone_office', $page->phone_office) }}"></div>
                    <div class="col-md-4"><label class="form-label">Admissions email</label><input type="email" name="email_admissions" class="form-control" value="{{ old('email_admissions', $page->email_admissions) }}"></div>
                    <div class="col-md-4"><label class="form-label">General email</label><input type="email" name="email_general" class="form-control" value="{{ old('email_general', $page->email_general) }}"></div>
                    <div class="col-md-4"><label class="form-label">Website</label><input type="text" name="website" class="form-control" value="{{ old('website', $page->website) }}"></div>
                    <div class="col-12"><label class="form-label">Office hours (one per line)</label><textarea name="office_hours_text" rows="3" class="form-control">{{ old('office_hours_text', implode("\n", $page->office_hours ?? [])) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Support &amp; partnership</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Admission support title</label><input type="text" name="admission_support_title" class="form-control" value="{{ old('admission_support_title', $page->admission_support_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">Partnership title</label><input type="text" name="partnership_title" class="form-control" value="{{ old('partnership_title', $page->partnership_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">Admission support intro</label><textarea name="admission_support_intro" rows="2" class="form-control">{{ old('admission_support_intro', $page->admission_support_intro) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label">Partnership intro</label><textarea name="partnership_intro" rows="2" class="form-control">{{ old('partnership_intro', $page->partnership_intro) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label">Support items (one per line)</label><textarea name="admission_support_items_text" rows="5" class="form-control">{{ old('admission_support_items_text', implode("\n", $page->admission_support_items ?? [])) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label">Partnership items (one per line)</label><textarea name="partnership_items_text" rows="5" class="form-control">{{ old('partnership_items_text', implode("\n", $page->partnership_items ?? [])) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Faculty CTA, media, social, map</h5>
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Faculty CTA title</label><input type="text" name="faculty_cta_title" class="form-control" value="{{ old('faculty_cta_title', $page->faculty_cta_title) }}"></div>
                    <div class="col-12"><label class="form-label">Faculty CTA text</label><textarea name="faculty_cta_text" rows="2" class="form-control">{{ old('faculty_cta_text', $page->faculty_cta_text) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Faculty CTA URL</label><input type="text" name="faculty_cta_url" class="form-control" value="{{ old('faculty_cta_url', $page->faculty_cta_url) }}"></div>
                    <div class="col-md-6"><label class="form-label">Media title</label><input type="text" name="media_title" class="form-control" value="{{ old('media_title', $page->media_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">Media email</label><input type="email" name="email_media" class="form-control" value="{{ old('email_media', $page->email_media) }}"></div>
                    <div class="col-12"><label class="form-label">Media text</label><textarea name="media_text" rows="2" class="form-control">{{ old('media_text', $page->media_text) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Google Maps embed URL</label><textarea name="maps_embed_url" rows="2" class="form-control">{{ old('maps_embed_url', $page->maps_embed_url) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Form categories (one per line)</label><textarea name="form_categories_text" rows="4" class="form-control">{{ old('form_categories_text', implode("\n", $page->form_categories ?? [])) }}</textarea></div>
                    @php $social = old('social_labels') ? [] : ($page->social_links ?? []); @endphp
                    @if(old('social_labels'))
                        @php foreach(old('social_labels', []) as $i => $l) { $social[] = ['label'=>$l,'icon'=>old('social_icons')[$i]??'','url'=>old('social_urls')[$i]??'']; } @endphp
                    @endif
                    @for($i = 0; $i < max(6, count($social)); $i++)
                    <div class="col-12 border rounded p-3 bg-light">
                        <div class="row g-2">
                            <div class="col-md-4"><input type="text" name="social_labels[]" class="form-control form-control-sm" placeholder="Label" value="{{ $social[$i]['label'] ?? '' }}"></div>
                            <div class="col-md-3"><input type="text" name="social_icons[]" class="form-control form-control-sm" placeholder="Icon class" value="{{ $social[$i]['icon'] ?? 'fab fa-facebook-f' }}"></div>
                            <div class="col-md-5"><input type="text" name="social_urls[]" class="form-control form-control-sm" placeholder="URL" value="{{ $social[$i]['url'] ?? '' }}"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Immediate assistance &amp; footer tagline</h5>
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Immediate title</label><input type="text" name="immediate_title" class="form-control" value="{{ old('immediate_title', $page->immediate_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">Call phone</label><input type="text" name="immediate_call" class="form-control" value="{{ old('immediate_call', $page->immediate_call) }}"></div>
                    <div class="col-md-6"><label class="form-label">WhatsApp</label><input type="text" name="immediate_whatsapp" class="form-control" value="{{ old('immediate_whatsapp', $page->immediate_whatsapp) }}"></div>
                    <div class="col-md-6"><label class="form-label">Intro session URL</label><input type="text" name="immediate_intro_url" class="form-control" value="{{ old('immediate_intro_url', $page->immediate_intro_url) }}"></div>
                    <div class="col-md-6"><label class="form-label">Apply URL</label><input type="text" name="immediate_apply_url" class="form-control" value="{{ old('immediate_apply_url', $page->immediate_apply_url) }}"></div>
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
                @if($page->hero_image)<img src="{{ asset($page->hero_image) }}" class="img-fluid rounded mb-3 border" alt="">@endif
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remove_hero_image" value="1" id="remove_hero_image"><label class="form-check-label text-danger small" for="remove_hero_image">Remove image</label></div>
                <input type="file" name="hero_image" class="form-control" accept="image/*">
            </div>
            <div class="bns-card p-4">
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $page->is_active) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Page published</label></div>
                <button type="submit" class="btn btn-sop-primary w-100">Save Changes</button>
            </div>
        </div>
    </div>
</form>
@endsection
