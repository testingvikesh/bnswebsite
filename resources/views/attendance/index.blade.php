@extends('layouts.front')

@section('title', 'Attendance')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pay-now.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/attendance.css') }}" />
@endpush

@section('content')
<div class="bns-attendance" data-attendance
     data-lookup-url="{{ route('attendance.lookup') }}"
     data-mark-url="{{ route('attendance.mark') }}"
     data-register-url="{{ route('attendance.register-and-mark') }}"
     data-csrf="{{ csrf_token() }}">
    @include('partials.page-header', [
        'title' => 'Attendance',
        'bgImage' => asset('assets/images/backgrounds/page-header-bg.jpg'),
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Attendance'],
        ],
    ])

    <section class="bns-pay-now__section">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <article class="bns-pay-now__banner bns-attendance__banner">
                <p class="bns-pay-now__eyebrow">🌟 Session Attendance 🌟</p>
                <h2>Mark your Introduction Session attendance</h2>
                <ul class="bns-pay-now__points">
                    <li>Find your booking using Reference No., mobile number, or registered email.</li>
                    <li>Confirm your details and mark attendance for your session.</li>
                    <li>Not registered yet? Book your spot now — registration and attendance confirm together.</li>
                    <li>A confirmation email will be sent after attendance is marked.</li>
                </ul>
                <p class="bns-pay-now__footer">Business Navachar School (BNS) 🇮🇳</p>
                <div class="bns-pay-now__actions">
                    <button type="button" class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary" id="bnsOpenAttendanceModalBtn" data-bs-toggle="modal" data-bs-target="#bnsAttendanceLookupModal">
                        <i class="fas fa-user-check"></i> Mark Attendance
                    </button>
                </div>
            </article>
        </div>
    </section>

    <div class="modal fade bns-membership-upload-modal"
         id="bnsAttendanceLookupModal"
         tabindex="-1"
         aria-labelledby="bnsAttendanceLookupModalLabel"
         aria-hidden="true"
         data-open-on-load="1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content bns-pay-now-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="bnsAttendanceLookupModalLabel">Find Your Session Booking</h5>
                    @include('partials.modal-close-button', ['onLight' => true])
                </div>
                <div class="modal-body">
                    <div id="bnsAttendanceLookupPanel">
                        <div class="bns-pay-now__tabs" role="tablist">
                            <button type="button" class="bns-pay-now__tab is-active" data-attendance-tab="reference">Last 4 Digits of Reference No</button>
                            <button type="button" class="bns-pay-now__tab" data-attendance-tab="mobile">Mobile Number</button>
                            <button type="button" class="bns-pay-now__tab" data-attendance-tab="email">Registered Email ID</button>
                        </div>

                        <form id="bnsAttendanceLookupForm" class="bns-pay-now__lookup-form" action="javascript:void(0)" method="post" onsubmit="return false;">
                            <div class="bns-pay-now__lookup-panel is-active-panel" data-attendance-panel="reference">
                                <label class="form-label" for="attendance_reference_last4">Enter last 4 digits of your Reference Number</label>
                                <input type="text" class="form-control" id="attendance_reference_last4" inputmode="numeric" maxlength="4" pattern="\d{4}" placeholder="e.g. 0042" autocomplete="off">
                                <small class="text-muted">From your session booking confirmation email (e.g. BNS-ENQ-2026-<strong>0042</strong>)</small>
                            </div>
                            <div class="bns-pay-now__lookup-panel" data-attendance-panel="mobile" hidden>
                                <label class="form-label" for="attendance_lookup_mobile">Enter registered mobile number</label>
                                <input type="tel" class="form-control" id="attendance_lookup_mobile" inputmode="numeric" maxlength="15" placeholder="e.g. 9427220997" autocomplete="tel">
                                <small class="text-muted">Use the same mobile number from your session booking.</small>
                            </div>
                            <div class="bns-pay-now__lookup-panel" data-attendance-panel="email" hidden>
                                <label class="form-label" for="attendance_lookup_email">Enter registered email ID</label>
                                <input type="email" class="form-control" id="attendance_lookup_email" placeholder="email used while booking" autocomplete="email">
                                <small class="text-muted">Use the same email ID from your session booking confirmation.</small>
                            </div>
                            <input type="hidden" id="attendance_lookup_type" value="reference">
                            <button type="button" class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary mt-3" id="bnsAttendanceLookupBtn">
                                Attendance Confirm
                            </button>
                            <p class="bns-pay-now__lookup-msg" id="bnsAttendanceLookupMsg" hidden></p>
                        </form>

                        <div class="bns-attendance__walkin" id="bnsAttendanceWalkinCta" hidden>
                            <p class="bns-attendance__walkin-title">If you have not registered</p>
                            <p class="bns-attendance__walkin-text">Don't worry — book your spot now. We will register you and confirm attendance together.</p>
                            <button type="button" class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary" id="bnsAttendanceOpenRegisterBtn">
                                <i class="fas fa-rocket"></i> Book your spot now
                            </button>
                        </div>

                        <div class="bns-pay-now__results" id="bnsAttendanceResults" hidden>
                            <h6 class="mb-3">Booking Details</h6>
                            <div id="bnsAttendanceResultsList"></div>
                        </div>
                    </div>

                    <div id="bnsAttendanceRegisterPanel" hidden>
                        <button type="button" class="btn btn-link bns-attendance__back-btn px-0 mb-2" id="bnsAttendanceBackToLookupBtn">
                            ← Back to find booking
                        </button>
                        <h6 class="mb-1">Book your spot now</h6>
                        <p class="text-muted small mb-3">Fill the form below. On submit, registration and attendance will both be confirmed.</p>

                        @include('partials.quick-register-form', [
                            'register' => $stickyIntro ?? config('home.sticky_cta.intro_session', []),
                            'formId' => 'bnsAttendanceRegisterForm',
                            'formAction' => route('attendance.register-and-mark'),
                            'formSource' => 'attendance-walkin',
                            'submitLabel' => 'Register & Confirm Attendance',
                        ])
                        <p class="bns-pay-now__lookup-msg mt-3" id="bnsAttendanceRegisterMsg" hidden></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ bns_vasset('assets/js/attendance.js') }}"></script>
@endpush
