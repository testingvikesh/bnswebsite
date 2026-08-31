@php
    $inquiry = $stickyInquiry ?? [];
@endphp

<div class="modal fade bns-inquiry-modal bns-vision-modal" id="bnsInquiryModal" tabindex="-1" aria-labelledby="bnsInquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $inquiry['program_label'] ?? 'Business Navachar School' }}</span>
                    <h5 class="modal-title" id="bnsInquiryModalLabel">Inquiry Now</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                <p class="bns-intro-session-modal__intro">{!! bns_rich_text(config('home.sticky_cta.inquiry.intro', 'Share your question and our Admission Team will contact you with program details, eligibility, fees, and next steps.')) !!}</p>

                @if($errors->any() && old('form_source') === 'inquiry-modal')
                    <div class="alert alert-danger bns-intro-session-modal__alert" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @include('partials.inquiry-form', [
                    'inquiry' => $inquiry,
                ])
            </div>
        </div>
    </div>
</div>

@if(old('form_source') === 'inquiry-modal' && $errors->any())
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById('bnsInquiryModal');
        if (modalEl && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    });
    </script>
    @endpush
@endif
