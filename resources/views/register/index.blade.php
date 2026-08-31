@extends('layouts.front')

@section('title', 'Registration')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/register.css') }}" />
@endpush

@section('content')
<div class="bns-register-page">
    @include('partials.page-header', [
        'title' => 'BNS Registration',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Admissions', 'url' => route('admissions.index')],
            ['label' => 'BNS Registration'],
        ],
    ])

    <section class="bns-register-intro">
        <div class="container">
            <p class="bns-register-intro__eyebrow"><i class="fas fa-graduation-cap"></i> Business Navachar School</p>
            <p class="bns-register-intro__text">Select your BNS program — share basic details in a quick form and our Admission Team will guide you.</p>
        </div>
    </section>

    <section class="bns-register-hub">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success bns-register-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any() && old('form_source') !== 'register-quick-modal')
                <div class="alert alert-danger bns-register-alert" role="alert">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="bns-register-hub__label" id="registerHubLabel">Choose your admission program</p>

            <p class="bns-register-direct-note" id="registerDirectNote" hidden>
                <button type="button" class="bns-register-direct-note__btn" id="registerShowAllPrograms">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Choose another program
                </button>
            </p>

            <div class="bns-register-boxes bns-audience__grid bns-audience__grid--pro" id="registerBoxes">
                @foreach($registerPrograms as $index => $program)
                    @php
                        $theme = $program['card_theme'] ?? [];
                    @endphp
                    <a
                        href="{{ !empty($program['program_slug']) ? route('programs.show', $program['program_slug']) : '#' }}"
                        class="bns-audience-card bns-audience-card--pro"
                        data-target="{{ $program['id'] }}"
                        data-program-title="{{ $program['title'] }}"
                        data-program-subtitle="{{ $program['subtitle'] }}"
                        data-contact-program="{{ $program['contact_program'] }}"
                        data-contact-category="{{ $program['category'] }}"
                        @if(!empty($program['combo'])) data-combo="{{ json_encode($program['combo']) }}" @endif
                    >
                        <div
                            class="bns-audience-card__inner"
                            style="--bns-card-bg: {{ $theme['bg'] ?? '#4a0e1c' }}; --bns-card-accent: {{ $theme['accent'] ?? '#ffb08f' }};"
                        >
                            <div class="bns-audience-card__media">
                                @if(!empty($program['image_url']))
                                    <img
                                        src="{{ $program['image_url'] }}"
                                        alt="{{ $program['image_alt'] ?? $program['title'] }}"
                                        class="bns-audience-card__image"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                @endif
                            </div>
                            <div class="bns-audience-card__content">
                                <span class="bns-audience-card__rule" aria-hidden="true"></span>
                                <p class="bns-audience-card__title-top">{{ $program['title_top'] ?? strtoupper($program['title']) }}</p>
                                @if(!empty($program['title_main']))
                                    <h2 class="bns-audience-card__title-main">{{ $program['title_main'] }}</h2>
                                @endif
                                <span class="bns-audience-card__rule" aria-hidden="true"></span>
                                <p class="bns-audience-card__desc">{{ $program['subtitle'] }}</p>
                                <span class="bns-audience-card__cta">
                                    {{ config('site.apply_cta_label', 'Book Your Spot Now') }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @include('register.partials.quick-inquiry-modal')

            <div class="bns-register-full-forms" id="registerFullForms" hidden>
            <div class="bns-register-panel" id="panel-youth-school" data-form="youth-school">
                <div class="bns-register-panel__header">
                    <h2 class="bns-register-panel__title">Youth School Admission Form</h2>
                    <p class="bns-register-panel__subtitle">For College Students, Graduates, Post Graduates &amp; Young Professionals</p>
                </div>
                @include('register.partials.panel-toolbar')
                <div class="bns-register-panel__body">
                    @include('register.partials.youth-form')
                </div>
            </div>

            <div class="bns-register-panel" id="panel-student-school" data-form="student-school">
                <div class="bns-register-panel__header bns-register-panel__header--student">
                    <h2 class="bns-register-panel__title">Student School Admission Form</h2>
                    <p class="bns-register-panel__subtitle">For Std 6 to Std 11 Students</p>
                </div>
                @include('register.partials.panel-toolbar')
                <div class="bns-register-panel__body">
                    @include('register.partials.student-form')
                </div>
            </div>

            <div class="bns-register-panel" id="panel-women-school" data-form="women-school">
                <div class="bns-register-panel__header bns-register-panel__header--women">
                    <h2 class="bns-register-panel__title">Women Entrepreneurship School Admission Form</h2>
                    <p class="bns-register-panel__subtitle">For Housewives, Women Entrepreneurs &amp; Working Women</p>
                </div>
                @include('register.partials.panel-toolbar')
                <div class="bns-register-panel__body">
                    @include('register.partials.women-form')
                </div>
            </div>

            <div class="bns-register-panel" id="panel-working-women-school" data-form="working-women-school">
                <div class="bns-register-panel__header bns-register-panel__header--working-women">
                    <h2 class="bns-register-panel__title">Working Women Leadership &amp; Career Growth School</h2>
                    <p class="bns-register-panel__subtitle">For Working Women, Professionals, Executives, Managers &amp; Career-Oriented Women</p>
                </div>
                @include('register.partials.panel-toolbar')
                <div class="bns-register-panel__body">
                    @include('register.partials.working-women-form')
                </div>
            </div>

            <div class="bns-register-panel" id="panel-job-professional-school" data-form="job-professional-school">
                <div class="bns-register-panel__header bns-register-panel__header--job-professional">
                    <h2 class="bns-register-panel__title">Job Professional Growth School Admission Form</h2>
                    <p class="bns-register-panel__subtitle">For Employees, Professionals, Executives, Managers, Government Officers &amp; Career-Oriented Individuals</p>
                </div>
                @include('register.partials.panel-toolbar')
                <div class="bns-register-panel__body">
                    @include('register.partials.job-professional-form')
                </div>
            </div>

            <div class="bns-register-panel" id="panel-business-growth-school" data-form="business-growth-school">
                <div class="bns-register-panel__header bns-register-panel__header--business-growth">
                    <h2 class="bns-register-panel__title">Business Growth School Admission Form</h2>
                    <p class="bns-register-panel__subtitle">For Business Owners, Entrepreneurs, Startup Founders, Traders, Manufacturers, Professionals &amp; Family Business Leaders</p>
                </div>
                @include('register.partials.panel-toolbar')
                <div class="bns-register-panel__body">
                    @include('register.partials.business-growth-form')
                </div>
            </div>
            </div>
        </div>
    </section>

    <div class="bns-register-sticky-bar" id="registerStickyBar" hidden>
        <div class="bns-register-sticky-bar__inner">
            <a href="#applySubmit" id="stickyApplyBtn" class="thm-btn bns-admission-form__btn--apply">Submit <i class="fas fa-paper-plane"></i></a>
            @if(!empty($siteHeader['phone']))
            <a href="{{ $siteHeader['phone_href'] }}" class="thm-btn bns-admission-form__btn--talk">Call <i class="fas fa-phone"></i></a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var boxes = document.querySelectorAll('#registerBoxes [data-target]');
    var panels = document.querySelectorAll('.bns-register-panel');
    var stickyBar = document.getElementById('registerStickyBar');
    var stickyApplyBtn = document.getElementById('stickyApplyBtn');
    var fullFormsWrap = document.getElementById('registerFullForms');
    var quickModalEl = document.getElementById('bnsRegisterQuickModal');
    var quickModal = quickModalEl && window.bootstrap ? bootstrap.Modal.getOrCreateInstance(quickModalEl) : null;
    var quickProgramId = document.getElementById('bnsRegisterQuickProgramId');
    var quickInterestedProgram = document.getElementById('bnsRegisterQuickInterestedProgram');
    var quickCategory = document.getElementById('bnsRegisterQuickCategory');
    var quickMessage = document.getElementById('bnsRegisterQuickMessage');
    var quickTitle = document.getElementById('bnsRegisterQuickModalLabel');
    var quickIntro = document.getElementById('bnsRegisterQuickIntro');
    var quickFullFormBtn = document.getElementById('bnsRegisterQuickFullFormBtn');
    var quickCombo = document.getElementById('bnsRegisterQuickCombo');
    var quickComboOptions = document.getElementById('bnsRegisterQuickComboOptions');
    var activeQuickTarget = null;

    var submitIds = {
        'youth-school': 'applySubmit',
        'student-school': 'studentApplySubmit',
        'women-school': 'womenApplySubmit',
        'working-women-school': 'workingWomenApplySubmit',
        'job-professional-school': 'jobProfessionalApplySubmit',
        'business-growth-school': 'businessGrowthApplySubmit'
    };

    // Old direct-link hashes for the two forms now reachable via the combo chooser.
    var hashAliases = {
        'job-professional-school': 'business-job-professional-batch',
        'working-women-school': 'business-job-professional-batch'
    };

    var validTargets = Object.keys(submitIds).concat(['business-job-professional-batch']);

    function getHashTarget() {
        var hash = (window.location.hash || '').replace(/^#/, '');
        if (hashAliases[hash]) hash = hashAliases[hash];
        return validTargets.indexOf(hash) >= 0 ? hash : null;
    }

    function findBox(targetId) {
        return document.querySelector('[data-target="' + targetId + '"]');
    }

    function setDirectFormMode(enabled) {
        var boxesEl = document.getElementById('registerBoxes');
        var hubLabel = document.getElementById('registerHubLabel');
        var directNote = document.getElementById('registerDirectNote');
        var hub = document.querySelector('.bns-register-hub');

        if (boxesEl) boxesEl.hidden = !!enabled;
        if (hubLabel) hubLabel.hidden = !!enabled;
        if (directNote) directNote.hidden = !enabled;
        if (hub) hub.classList.toggle('bns-register-hub--direct', !!enabled);
    }

    function setActiveBox(targetId) {
        boxes.forEach(function (b) {
            var isActive = b.getAttribute('data-target') === targetId;
            b.classList.toggle('is-active', isActive);
            b.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        });
    }

    function fillQuickModal(box) {
        if (!box) return;
        var title = box.getAttribute('data-program-title') || 'BNS Program';
        var subtitle = box.getAttribute('data-program-subtitle') || '';
        var contactProgram = box.getAttribute('data-contact-program') || '';
        var category = box.getAttribute('data-contact-category') || 'Other';
        var targetId = box.getAttribute('data-target');
        var comboRaw = box.getAttribute('data-combo');
        var combo = null;
        if (comboRaw) {
            try { combo = JSON.parse(comboRaw); } catch (e) { combo = null; }
        }

        activeQuickTarget = targetId;
        if (quickProgramId) quickProgramId.value = targetId || '';
        if (quickInterestedProgram) quickInterestedProgram.value = contactProgram;
        if (quickCategory) quickCategory.value = category;
        if (quickMessage) quickMessage.value = 'Registration request for ' + title + ' at Business Navachar School (BNS).';
        if (quickTitle) quickTitle.textContent = title;
        if (quickIntro) quickIntro.textContent = subtitle || 'Share your basic details — our Admission Team will contact you shortly.';

        if (combo && combo.length && quickCombo && quickComboOptions) {
            quickComboOptions.innerHTML = '';
            combo.forEach(function (option) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'bns-register-quick-form__combo-btn';
                btn.setAttribute('data-combo-target', option.id);
                btn.innerHTML = '<i class="' + (option.icon || 'fas fa-arrow-right') + '"></i> <span>' + option.label + '</span>';
                quickComboOptions.appendChild(btn);
            });
            quickCombo.hidden = false;
            if (quickFullFormBtn) quickFullFormBtn.hidden = true;
        } else {
            if (quickCombo) quickCombo.hidden = true;
            if (quickFullFormBtn) quickFullFormBtn.hidden = false;
        }
    }

    function openQuickModal(box) {
        if (!box) return;
        fillQuickModal(box);
        setActiveBox(box.getAttribute('data-target'));
        if (quickModal) quickModal.show();
    }

    function openPanel(targetId, box, scrollToPanel) {
        if (fullFormsWrap) fullFormsWrap.hidden = false;
        if (stickyBar) stickyBar.hidden = false;
        setDirectFormMode(true);

        panels.forEach(function (panel) { panel.classList.remove('is-open'); });
        setActiveBox(targetId);

        var panel = document.getElementById('panel-' + targetId);
        if (panel) {
            panel.classList.add('is-open');
            if (stickyApplyBtn && submitIds[targetId]) {
                stickyApplyBtn.setAttribute('href', '#' + submitIds[targetId]);
                stickyApplyBtn.innerHTML = 'Submit <i class="fas fa-paper-plane"></i>';
            }
            if (stickyBar && stickyBar._observer) {
                stickyBar._observer.disconnect();
                stickyBar._observer.observe(panel);
            }
            if (scrollToPanel) {
                setTimeout(function () {
                    panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 80);
            }
        }
    }

    // Boxes are now direct links to each program's detail page (/programs/{slug}),
    // so no click-to-modal binding here. The quick modal is still opened via the
    // hash-based deep link / session-based reopen flows below.

    if (quickFullFormBtn) {
        quickFullFormBtn.addEventListener('click', function () {
            if (!activeQuickTarget) return;
            if (quickModal) quickModal.hide();
            openPanel(activeQuickTarget, findBox(activeQuickTarget), true);
        });
    }

    if (quickComboOptions) {
        quickComboOptions.addEventListener('click', function (event) {
            var btn = event.target.closest('[data-combo-target]');
            if (!btn) return;
            var comboTargetId = btn.getAttribute('data-combo-target');
            if (quickModal) quickModal.hide();
            openPanel(comboTargetId, findBox(activeQuickTarget), true);
        });
    }

    var showAllBtn = document.getElementById('registerShowAllPrograms');
    if (showAllBtn) {
        showAllBtn.addEventListener('click', function () {
            setDirectFormMode(false);
            if (fullFormsWrap) fullFormsWrap.hidden = true;
            if (stickyBar) stickyBar.hidden = true;
            panels.forEach(function (panel) { panel.classList.remove('is-open'); });
            var boxesEl = document.getElementById('registerBoxes');
            if (boxesEl) boxesEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    window.addEventListener('hashchange', function () {
        var hashTarget = getHashTarget();
        if (!hashTarget) return;
        openQuickModal(findBox(hashTarget));
    });

    if (stickyBar) {
        var page = document.querySelector('.bns-register-page');
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.target.classList.contains('is-open')) {
                    stickyBar.classList.toggle('is-visible', entry.isIntersecting);
                    if (page) page.classList.toggle('has-sticky-bar', entry.isIntersecting);
                }
            });
        }, { threshold: 0.05, rootMargin: '-80px 0px 0px 0px' });
        stickyBar._observer = observer;
    }

    function bindDobAge(dobId, ageId) {
        var dobInput = document.getElementById(dobId);
        var ageInput = document.getElementById(ageId);
        if (!dobInput || !ageInput) return;
        dobInput.addEventListener('change', function () {
            if (!dobInput.value) return;
            var birth = new Date(dobInput.value);
            var today = new Date();
            var age = today.getFullYear() - birth.getFullYear();
            var m = today.getMonth() - birth.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
            if (age >= 0) ageInput.value = age;
        });
    }

    bindDobAge('date_of_birth', 'age');
    bindDobAge('student_date_of_birth', 'student_age');
    bindDobAge('women_date_of_birth', 'women_age');
    bindDobAge('ww_date_of_birth', 'ww_age');
    bindDobAge('jp_date_of_birth', 'jp_age');
    bindDobAge('bg_date_of_birth', 'bg_age');

    function bindPhoto(inputId, filenameId) {
        var photoInput = document.getElementById(inputId);
        var photoFilename = document.getElementById(filenameId);
        if (!photoInput || !photoFilename) return;
        photoInput.addEventListener('change', function () {
            photoFilename.textContent = photoInput.files.length ? photoInput.files[0].name : '';
        });
    }

    bindPhoto('photo', 'photoFilename');
    bindPhoto('student_photo', 'studentPhotoFilename');
    bindPhoto('women_photo', 'womenPhotoFilename');
    bindPhoto('ww_photo', 'wwPhotoFilename');
    bindPhoto('jp_photo', 'jpPhotoFilename');
    bindPhoto('bg_photo', 'bgPhotoFilename');

    @php
        $activeForm = old('form_type', session('active_form'));
        $quickProgram = old('register_program_id', session('register_quick_program'));
        $openQuickModal = old('form_source') === 'register-quick-modal' && $errors->any();
        $openFullForm = $activeForm && old('form_source') !== 'register-quick-modal' && $errors->any();
    @endphp

    var hashTarget = getHashTarget();
    if ({{ $openFullForm ? 'true' : 'false' }}) {
        openPanel('{{ $activeForm }}', findBox('{{ $activeForm }}'), true);
    } else if ({{ $openQuickModal ? 'true' : 'false' }}) {
        var quickProgramTarget = '{{ $quickProgram ?: 'youth-school' }}';
        if (hashAliases[quickProgramTarget]) quickProgramTarget = hashAliases[quickProgramTarget];
        var quickBox = findBox(quickProgramTarget);
        fillQuickModal(quickBox);
        if (quickModal) quickModal.show();
    } else if (hashTarget) {
        openQuickModal(findBox(hashTarget));
    }
})();
</script>
@endpush
