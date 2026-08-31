@php
    $ctaUrl = $resolveCta($event['cta'] ?? ['route' => 'register']);
    $ctaLabel = $event['cta']['label'] ?? 'Book Your Spot Now';
    $ctaIsRegister = ($event['cta']['route'] ?? 'register') === 'register';
@endphp

<article class="bns-event-spotlight">
    <div class="bns-event-spotlight__top">
        <div class="bns-event-spotlight__top-left">
            @if(!empty($event['session_number']))
                <span class="bns-event-spotlight__session-no" aria-label="Session {{ $event['session_number'] }}">{{ $event['session_number'] }}</span>
            @endif
            <span class="bns-event-spotlight__badge">
                <i class="fas fa-star" aria-hidden="true"></i> {{ $event['category'] ?? 'Induction Seminar' }}
            </span>
        </div>
        @if(!empty($event['date']))
            <div class="bns-event-spotlight__date">
                <span class="bns-event-spotlight__date-day">{{ \Illuminate\Support\Str::before($event['date'], ' ') }}</span>
                <span class="bns-event-spotlight__date-rest">{{ trim(\Illuminate\Support\Str::after($event['date'], ' ')) }}</span>
            </div>
        @endif
    </div>

    <div class="bns-event-spotlight__body">
        <h3 class="bns-event-spotlight__title">{{ $event['title'] ?? '' }}</h3>
        @if(!empty($event['tagline']))
            <p class="bns-event-spotlight__tagline">{!! bns_rich_text($event['tagline']) !!}</p>
        @endif

        <div class="bns-event-spotlight__meta">
            @if(!empty($event['time']))
                <span><i class="fas fa-clock" aria-hidden="true"></i> {{ $event['time'] }}</span>
            @endif
            @if(!empty($event['venue']))
                <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $event['venue'] }}</span>
            @endif
        </div>

        @if(!empty($event['audience']))
            <div class="bns-event-spotlight__audience">
                <p class="bns-event-spotlight__audience-label">Who Can Join</p>
                <p class="bns-event-spotlight__audience-text">{!! bns_rich_text($event['audience']) !!}</p>
            </div>
        @endif

        @if(!empty($event['guest_faculty']))
            <div class="bns-event-spotlight__audience">
                <p class="bns-event-spotlight__audience-label">BNS Team</p>
                <p class="bns-event-spotlight__audience-text">{!! bns_rich_text($event['guest_faculty']) !!}</p>
            </div>
        @endif

        @if(!empty($event['experience']))
            <div class="bns-event-spotlight__experience">
                <h4>What You'll Experience</h4>
                <ul class="list-unstyled">
                    @foreach($event['experience'] as $item)
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{!! bns_rich_text($item) !!}</span></li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!empty($event['benefits']))
            <div class="bns-event-spotlight__experience">
                <h4>Benefits</h4>
                <ul class="list-unstyled">
                    @foreach($event['benefits'] as $item)
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{!! bns_rich_text($item) !!}</span></li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!empty($event['highlights']))
            <div class="bns-event-spotlight__experience">
                <h4>Highlights</h4>
                <ul class="list-unstyled">
                    @foreach($event['highlights'] as $item)
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{!! bns_rich_text($item) !!}</span></li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!empty($event['seats']))
            <p class="bns-event-spotlight__seats">
                <i class="fas fa-ticket-alt" aria-hidden="true"></i> {!! bns_rich_text($event['seats']) !!}
            </p>
        @endif

        @if($ctaIsRegister)
            <button type="button" class="bns-event-spotlight__btn" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                {{ $ctaLabel }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </button>
        @else
            <a href="{{ $ctaUrl }}" class="bns-event-spotlight__btn">
                {{ $ctaLabel }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        @endif
    </div>
</article>
