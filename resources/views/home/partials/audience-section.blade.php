@php($audience = $audienceSection ?? config('home.audience_section', []))
@if(!empty($audience['cards']))
<section class="bns-audience" aria-labelledby="bns-audience-title">
    <div class="container">
        <div class="bns-audience__picker" id="bnsAudiencePicker">
            <div class="bns-audience__header wow fadeInUp" data-wow-duration="0.8s">
                @if(!empty($audience['tagline']))
                    <span class="bns-audience__tagline">{{ $audience['tagline'] }}</span>
                @endif
                <h2 class="bns-audience__title" id="bns-audience-title">
                    {{ rtrim($audience['title'] ?? 'Are you', '?') }}<span>?</span>
                </h2>
                @if(!empty($audience['subtitle']))
                    <p class="bns-audience__subtitle">{!! bns_rich_text($audience['subtitle']) !!}</p>
                @endif
                @if(!empty($audience['ownership_note']))
                    <p class="bns-audience__ownership">{!! bns_rich_text($audience['ownership_note']) !!}</p>
                @endif
            </div>

            <div class="bns-audience__grid bns-audience__grid--pro">
                @foreach($audience['cards'] as $index => $card)
                    @php($journeyId = $card['register_hash'] ?? '')
                    @php($hasDedicatedPage = !empty($card['dedicated_page']) && !empty($card['program_slug']))
                    @if($hasDedicatedPage)
                    <a
                        href="{{ route('programs.show', $card['program_slug']) }}"
                        class="bns-audience-card bns-audience-card--pro wow fadeInUp"
                        data-wow-duration="0.85s"
                        data-wow-delay="{{ 60 + ($index * 50) }}ms"
                    >
                        @include('home.partials.audience-card-pro', ['card' => $card, 'img' => $img ?? null])
                    </a>
                    @else
                    <button
                        type="button"
                        class="bns-audience-card bns-audience-card--pro wow fadeInUp"
                        data-wow-duration="0.85s"
                        data-wow-delay="{{ 60 + ($index * 50) }}ms"
                        data-audience-card="{{ $journeyId }}"
                        aria-expanded="false"
                    >
                        @include('home.partials.audience-card-pro', ['card' => $card, 'img' => $img ?? null])
                    </button>
                    @endif
                @endforeach
            </div>
        </div>

        @if(!empty($audienceJourneys))
            @include('home.partials.audience-journey', [
                'audienceJourneys' => $audienceJourneys,
                'contactFormConfig' => $contactFormConfig ?? config('contact.form', []),
            ])
        @endif
    </div>
</section>

@push('scripts')
<script>
(function () {
    var picker = document.getElementById('bnsAudiencePicker');
    var journeyRoot = document.getElementById('bnsAudienceJourney');
    if (!picker || !journeyRoot) return;

    var cards = picker.querySelectorAll('[data-audience-card]');
    var panels = journeyRoot.querySelectorAll('[data-journey-panel]');
    var stepOrder = ['details', 'eligibility', 'session', 'syllabus', 'introduction', 'admission'];
    var activePanel = null;
    var activeStep = 0;

    function showPanel(id) {
        panels.forEach(function (panel) {
            var isActive = panel.getAttribute('data-journey-panel') === id;
            panel.hidden = !isActive;
            if (isActive) activePanel = panel;
        });
        journeyRoot.hidden = false;
        picker.hidden = true;
        setStep(0);
        requestAnimationFrame(scrollActiveStepIntoView);
        journeyRoot.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hidePanel() {
        journeyRoot.hidden = true;
        picker.hidden = false;
        cards.forEach(function (card) {
            card.classList.remove('is-active');
            card.setAttribute('aria-expanded', 'false');
        });
        activePanel = null;
        picker.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function setStep(index) {
        if (!activePanel || index < 0 || index >= stepOrder.length) return;
        activeStep = index;
        var stepKey = stepOrder[index];

        activePanel.querySelectorAll('[data-journey-step]').forEach(function (btn, i) {
            btn.classList.toggle('is-active', i === index);
        });

        activePanel.querySelectorAll('[data-journey-content]').forEach(function (block) {
            block.hidden = block.getAttribute('data-journey-content') !== stepKey;
            block.classList.toggle('is-active', block.getAttribute('data-journey-content') === stepKey);
        });

        var prevBtn = activePanel.querySelector('[data-journey-prev]');
        var nextBtn = activePanel.querySelector('[data-journey-next]');
        if (prevBtn) prevBtn.disabled = index === 0;
        if (nextBtn) {
            nextBtn.disabled = index === stepOrder.length - 1;
            nextBtn.innerHTML = index === stepOrder.length - 1
                ? 'Finish <i class="fas fa-check"></i>'
                : 'Next <i class="fas fa-chevron-right"></i>';
        }

        scrollActiveStepIntoView();
    }

    function scrollActiveStepIntoView() {
        if (!activePanel) return;
        var activeBtn = activePanel.querySelector('[data-journey-step].is-active');
        var stepsNav = activePanel.querySelector('.bns-audience-journey__steps-scroll');
        if (!activeBtn || !stepsNav) return;

        var navRect = stepsNav.getBoundingClientRect();
        var btnRect = activeBtn.getBoundingClientRect();
        var scrollLeft = stepsNav.scrollLeft + (btnRect.left - navRect.left) - (navRect.width / 2) + (btnRect.width / 2);

        stepsNav.scrollTo({
            left: Math.max(0, scrollLeft),
            behavior: 'smooth'
        });
    }

    cards.forEach(function (card) {
        card.addEventListener('click', function () {
            var id = card.getAttribute('data-audience-card');
            cards.forEach(function (c) {
                c.classList.toggle('is-active', c === card);
                c.setAttribute('aria-expanded', c === card ? 'true' : 'false');
            });
            showPanel(id);
        });
    });

    journeyRoot.querySelectorAll('[data-journey-back]').forEach(function (btn) {
        btn.addEventListener('click', hidePanel);
    });

    journeyRoot.querySelectorAll('[data-journey-step]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!activePanel || btn.closest('[data-journey-panel]') !== activePanel) return;
            var key = btn.getAttribute('data-journey-step');
            var index = stepOrder.indexOf(key);
            if (index >= 0) setStep(index);
        });
    });

    journeyRoot.querySelectorAll('[data-journey-prev]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!activePanel || btn.closest('[data-journey-panel]') !== activePanel) return;
            setStep(activeStep - 1);
        });
    });

    journeyRoot.querySelectorAll('[data-journey-next]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!activePanel || btn.closest('[data-journey-panel]') !== activePanel) return;
            if (activeStep >= stepOrder.length - 1) {
                hidePanel();
                return;
            }
            setStep(activeStep + 1);
        });
    });
})();
</script>
@endpush
@endif
