@php
    $intro = $stickyIntroSession ?? [];
    $introSessionPage = config('admission.pages.introduction-session', []);
    $openOnLoad = !empty($openOnLoad);
@endphp

@if($openOnLoad)
    <div class="modal-backdrop fade show" data-bns-intro-session-backdrop></div>
@endif

<div
    class="modal fade bns-intro-session-modal bns-vision-modal{{ $openOnLoad ? ' show' : '' }}"
    id="bnsIntroSessionModal"
    tabindex="-1"
    aria-labelledby="bnsIntroSessionModalLabel"
    aria-hidden="{{ $openOnLoad ? 'false' : 'true' }}"
    @if($openOnLoad) style="display: block;" @endif
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $intro['program_label'] ?? 'Business Navachar School' }}</span>
                    <h5 class="modal-title" id="bnsIntroSessionModalLabel">Introduction Session Admission</h5>
                </div>
                <button
                    type="button"
                    class="bns-modal-close"
                    data-bs-dismiss="modal"
                    data-bns-close-modal="bnsIntroSessionModal"
                    aria-label="Close"
                    id="bnsIntroSessionCloseBtn"
                    onclick="if(window.bnsCloseIntroModal){window.bnsCloseIntroModal();}else{var m=document.getElementById('bnsIntroSessionModal');if(m){m.classList.remove('show');m.classList.add('bns-modal-is-closed');m.style.setProperty('display','none','important');}document.querySelectorAll('.modal-backdrop,[data-bns-intro-session-backdrop]').forEach(function(n){n.remove();});document.body.classList.remove('modal-open');document.body.style.removeProperty('overflow');}return false;"
                >
                    <svg class="bns-modal-close__icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M18.3 5.71a1 1 0 0 0-1.41 0L12 10.59 7.11 5.7A1 1 0 0 0 5.7 7.11L10.59 12l-4.89 4.89a1 1 0 1 0 1.41 1.41L12 13.41l4.89 4.89a1 1 0 0 0 1.41-1.41L13.41 12l4.89-4.89a1 1 0 0 0 0-1.4z"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="bns-intro-session-modal__intro">{!! bns_rich_text($introSessionPage['intro'] ?? 'Attend a free introduction session to understand our programs, faculty, learning methodology, and outcomes.') !!}</p>

                @if(($errors->any() && old('form_source') === 'intro-session-modal') || (session('error') && old('form_source') === 'intro-session-modal'))
                    <div class="alert alert-danger bns-intro-session-modal__alert" role="alert">
                        @if(session('error'))
                            <p class="mb-0">{{ session('error') }}</p>
                        @endif
                        @if($errors->any() && old('form_source') === 'intro-session-modal')
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                @include('partials.introduction-session-form', [
                    'intro' => $intro,
                    'contactFormConfig' => config('contact.form', []),
                ])
            </div>
        </div>
    </div>
</div>
