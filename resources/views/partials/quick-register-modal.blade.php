@php
    $register = $stickyRegister ?? [];
    $openOnLoad = !empty($openOnLoad);
@endphp

@if($openOnLoad)
    <div class="modal-backdrop fade show" data-bns-quick-register-backdrop></div>
@endif

<div
    class="modal fade bns-quick-register-modal bns-vision-modal{{ $openOnLoad ? ' show' : '' }}"
    id="bnsQuickRegisterModal"
    tabindex="-1"
    aria-labelledby="bnsQuickRegisterModalLabel"
    aria-hidden="{{ $openOnLoad ? 'false' : 'true' }}"
    @if($openOnLoad) style="display: block;" @endif
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow" id="bnsQuickRegisterEyebrow">{{ $register['program_label'] ?? 'Business Navachar School' }}</span>
                    <h5 class="modal-title" id="bnsQuickRegisterModalLabel">{{ config('site.apply_cta_label', 'Book Your Spot Now') }}</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                <p class="bns-intro-session-modal__intro" id="bnsQuickRegisterIntro">
                    Share your basic details — our Admission Team will contact you shortly. No need to leave this page.
                </p>

                @if($errors->any() && old('form_source') === 'register-quick-modal')
                    <div class="alert alert-danger bns-intro-session-modal__alert" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @include('partials.quick-register-form', [
                    'register' => $register,
                ])
            </div>
        </div>
    </div>
</div>
