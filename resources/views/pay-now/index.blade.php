@extends('layouts.front')

@section('title', 'Pay Now')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pay-now.css') }}" />
@endpush

@section('content')
@php
    $banner = $banner ?? config('pay_now.banner', []);
    $instructions = config('pay_now.instructions', []);
    $amountLabel = config('pay_now.amount_label', '11,800');
@endphp

<div class="bns-pay-now" data-pay-now data-csrf="{{ csrf_token() }}">
    @include('partials.page-header', [
        'title' => 'Pay Now',
        'bgImage' => asset('assets/images/backgrounds/page-header-bg.jpg'),
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Pay Now'],
        ],
    ])

    <section class="bns-pay-now__section">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <article class="bns-pay-now__banner bns-pay-now__banner--instructions">
                <div class="bns-pay-now__lang-tabs" role="tablist" aria-label="Instruction language">
                    @foreach($instructions as $lang => $block)
                        <button
                            type="button"
                            class="bns-pay-now__lang-tab{{ $lang === 'en' ? ' is-active' : '' }}"
                            data-pay-now-lang="{{ $lang }}"
                        >{{ $block['label'] ?? strtoupper($lang) }}</button>
                    @endforeach
                </div>

                @foreach($instructions as $lang => $block)
                    <div
                        class="bns-pay-now__lang-panel{{ $lang === 'en' ? ' is-active' : '' }}"
                        data-pay-now-lang-panel="{{ $lang }}"
                        @if($lang !== 'en') hidden @endif
                    >
                        <p class="bns-pay-now__eyebrow">🌟 {{ $block['title'] ?? 'IMPORTANT ADMISSION INSTRUCTIONS' }} 🌟</p>
                        <h2>🎓 {{ $block['subtitle'] ?? '' }}</h2>

                        @foreach(($block['sections'] ?? []) as $section)
                            <div class="bns-pay-now__instr-section">
                                <h4>📢 {{ $section['heading'] ?? '' }}</h4>
                                <ul class="bns-pay-now__points">
                                    @foreach(($section['lines'] ?? []) as $line)
                                        <li>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach

                        <p class="bns-pay-now__instr-thanks">🙏 {{ $block['thanks'] ?? '' }}</p>
                        <p class="bns-pay-now__footer">🌟 {{ $block['brand'] ?? ($banner['footer'] ?? 'Business Navachar School (BNS)') }} 🇮🇳</p>
                        @if(!empty($block['tagline']))
                            <p class="bns-pay-now__instr-tagline">🚀 {{ $block['tagline'] }}</p>
                        @endif
                    </div>
                @endforeach

                <div class="bns-pay-now__actions">
                    <button type="button" class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsPaymentLookupModal">
                        <i class="fas fa-credit-card"></i> {{ $banner['payment_btn'] ?? 'Pay Now' }}
                    </button>
                </div>
            </article>
        </div>
    </section>
</div>

<div class="modal fade bns-membership-upload-modal"
     id="bnsPaymentLookupModal"
     tabindex="-1"
     aria-labelledby="bnsPaymentLookupModalLabel"
     aria-hidden="true"
     data-open-on-load="{{ !empty($openPayNowLookup) ? '1' : '0' }}">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content bns-pay-now-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="bnsPaymentLookupModalLabel">Pay Now — Session Admission</h5>
                @include('partials.modal-close-button', ['onLight' => true])
            </div>
            <div class="modal-body">
                <p class="bns-pay-now__new-intro mb-3">
                    Enter your details and submit. Mobile match uses existing booking; otherwise a new registration is created — then payment of
                    <strong>₹{{ $amountLabel }}</strong>.
                </p>

                <form method="POST" action="{{ route('pay-now.submit') }}" id="bnsPayNowSubmitForm" class="bns-pay-now__lookup-form">
                    @csrf
                    <input type="hidden" name="pay_now_submit" value="1">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label" for="pay_now_full_name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pay_now_full_name" name="full_name"
                                   value="{{ old('full_name') }}" required maxlength="255" autocomplete="name"
                                   placeholder="Enter your full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pay_now_email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="pay_now_email" name="email"
                                   value="{{ old('email') }}" required maxlength="255" autocomplete="email"
                                   placeholder="Enter your email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pay_now_mobile">Mobile No. <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="pay_now_mobile" name="mobile"
                                   value="{{ old('mobile') }}" required maxlength="15" inputmode="numeric"
                                   autocomplete="tel" placeholder="e.g. 9427220997">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="pay_now_program">Program <span class="text-danger">*</span></label>
                            <select class="form-select bns-pay-now__program-select" id="pay_now_program" name="interested_program" required>
                                @if(count($programs) > 1)
                                    <option value="" disabled @selected(! old('interested_program'))>Select program</option>
                                @endif
                                @foreach($programs as $programValue => $programLabel)
                                    <option value="{{ $programValue }}" @selected(old('interested_program') === $programValue || count($programs) === 1)>
                                        {{ $programLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="pay_now_gst_no">GST No. <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control" id="pay_now_gst_no" name="gst_no"
                                   value="{{ old('gst_no') }}" maxlength="20" autocomplete="off"
                                   placeholder="e.g. 27AAAAA0000A1Z5" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <button type="submit" class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary mt-3 w-100" id="bnsPayNowSubmitBtn">
                        <i class="fas fa-lock"></i> Submit &amp; Pay ₹{{ $amountLabel }}
                    </button>
                    <p class="small text-muted mt-2 mb-0">Mobile number is matched automatically — existing booking or new registration.</p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ bns_vasset('assets/js/pay-now.js') }}"></script>
@endpush
