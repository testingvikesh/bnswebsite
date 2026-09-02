@php
    $type = $event['type'] ?? 'introduction';
    $typeLabels = [
        'induction' => 'Induction Seminar',
        'introduction' => 'Introduction Session',
        'workshop' => 'Workshop',
        'seminar' => 'Seminar',
        'bootcamp' => 'Bootcamp',
        'networking' => 'Networking',
    ];
    $typeLabel = $typeLabels[$type] ?? 'Event';
    $ctaUrl = $resolveCta($event['cta'] ?? ['route' => 'register']);
    $ctaLabel = $event['cta']['label'] ?? 'Register Now';
    $ctaIsRegister = ($event['cta']['route'] ?? 'register') === 'register';
@endphp

<article class="bns-event-card bns-event-card--{{ $type }} {{ !empty($event['featured']) ? 'bns-event-card--featured' : '' }}">
    <div class="bns-event-card__header">
        <span class="bns-event-card__type">{{ $typeLabel }}</span>
        <h3>{{ bns_introduction_session_public_title($event) }}</h3>
    </div>

    <ul class="bns-event-card__meta list-unstyled">
        @if(!empty($event['date']))
            <li><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> {{ $event['date'] }}</li>
        @endif
        @if(!empty($event['time']))
            <li><i class="fas fa-clock"></i> <strong>Time:</strong> {{ $event['time'] }}</li>
        @endif
        @if(!empty($event['venue']))
            <li><i class="fas fa-map-marker-alt"></i> <strong>Venue:</strong> {{ $event['venue'] }}</li>
        @endif
        @if(!empty($event['duration']))
            <li><i class="fas fa-hourglass-half"></i> <strong>Duration:</strong> {{ $event['duration'] }}</li>
        @endif
        @if(!empty($event['category']))
            <li><i class="fas fa-bullseye"></i> <strong>Category:</strong> {{ $event['category'] }}</li>
        @endif
        @if(!empty($event['audience']))
            <li><i class="fas fa-users"></i> <strong>Who Can Join:</strong> {!! bns_rich_text($event['audience']) !!}</li>
        @endif
        @if(!empty($event['guest_faculty']))
            <li><i class="fas fa-users"></i> <strong>BNS Team:</strong> {!! bns_rich_text($event['guest_faculty']) !!}</li>
        @endif
        @if(!empty($event['speaker']))
            <li><i class="fas fa-microphone"></i> <strong>Speaker:</strong> {!! bns_rich_text($event['speaker']) !!}</li>
        @endif
    </ul>

    @if(!empty($event['experience']))
    <div class="bns-event-card__section">
        <h4><i class="fas fa-lightbulb"></i> What You'll Experience</h4>
        <ul class="list-unstyled">
            @foreach($event['experience'] as $item)
                <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!empty($event['benefits']))
    <div class="bns-event-card__section">
        <h4><i class="fas fa-trophy"></i> Benefits</h4>
        <ul class="list-unstyled">
            @foreach($event['benefits'] as $item)
                <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!empty($event['learning_outcomes']))
    <div class="bns-event-card__section">
        <h4><i class="fas fa-bullseye"></i> Learning Outcomes</h4>
        <ul class="list-unstyled">
            @foreach($event['learning_outcomes'] as $item)
                <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!empty($event['learn_items']))
    <div class="bns-event-card__section">
        <h4><i class="fas fa-rocket"></i> What You'll Learn</h4>
        <ul class="list-unstyled">
            @foreach($event['learn_items'] as $item)
                <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!empty($event['meet']) || !empty($event['opportunities']) || !empty($event['session']))
    <div class="bns-event-card__section">
        @if(!empty($event['meet']))
            <p><i class="fas fa-handshake"></i> <strong>Meet Entrepreneurs:</strong> {!! bns_rich_text($event['meet']) !!}</p>
        @endif
        @if(!empty($event['opportunities']))
            <p><i class="fas fa-briefcase"></i> <strong>Business Opportunities:</strong> {!! bns_rich_text($event['opportunities']) !!}</p>
        @endif
        @if(!empty($event['session']))
            <p><i class="fas fa-users"></i> <strong>Networking Session:</strong> {!! bns_rich_text($event['session']) !!}</p>
        @endif
    </div>
    @endif

    @if(!empty($event['certification']))
        <p class="bns-event-card__cert"><i class="fas fa-certificate"></i> <strong>Certification:</strong> {!! bns_rich_text($event['certification']) !!}</p>
    @endif

    @if(!empty($event['seats']))
        <p class="bns-event-card__seats"><i class="fas fa-ticket-alt"></i> {{ $event['seats'] }}</p>
    @endif

    @if($ctaIsRegister)
        <button type="button" class="bns-event-card__btn" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
            {{ $ctaLabel }} <i class="fas fa-arrow-right"></i>
        </button>
    @else
        <a href="{{ $ctaUrl }}" class="bns-event-card__btn">
            {{ $ctaLabel }} <i class="fas fa-arrow-right"></i>
        </a>
    @endif
</article>
