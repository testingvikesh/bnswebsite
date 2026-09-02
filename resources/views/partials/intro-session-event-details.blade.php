@php
    $selectable = $selectable ?? false;
    $sessions = bns_introduction_sessions($selectable);
    $event = $event ?? bns_first_introduction_session();
    $showBothOptions = $showBothOptions ?? true;
    $formId = $formId ?? 'bnsIntroSessionForm';
    $todaySession = bns_intro_session_number_for_date(
        \Illuminate\Support\Carbon::now('Asia/Kolkata')->toDateString()
    );
    $sessionNumbers = collect($sessions)
        ->map(fn (array $sessionOption) => (int) ($sessionOption['session_number'] ?? 0))
        ->filter(fn (int $number) => $number > 0)
        ->values()
        ->all();
    $defaultSession = ($todaySession && in_array($todaySession, $sessionNumbers, true))
        ? $todaySession
        : (int) (($sessions[0]['session_number'] ?? 0) ?: (bns_intro_session_selectable_numbers()[0] ?? 0));
    $selectedSession = (int) old('intro_session_number', $selectable ? $defaultSession : 0);
    $sessionCountLabel = match (count($sessions)) {
        1 => 'One Free Introduction Session',
        2 => 'Two Free Introduction Sessions',
        3 => 'Three Free Introduction Sessions',
        default => count($sessions).' Free Introduction Sessions',
    };
@endphp

@if($showBothOptions && count($sessions) > 1)
    <div class="bns-intro-session-modal__event">
        <p class="bns-intro-session-modal__event-label">{{ $selectable ? 'Select Session Date' : 'Choose Your Preferred Session' }}</p>
        <h6 class="bns-intro-session-modal__event-title">{{ $sessionCountLabel }}</h6>

        @if($selectable)
            <div class="bns-intro-session-modal__options" role="radiogroup" aria-label="Introduction session date">
                @foreach($sessions as $sessionOption)
                    @php
                        $sessionNo = (int) ($sessionOption['session_number'] ?? 0);
                        $optionId = $formId.'_intro_session_'.$sessionNo;
                        $isChecked = $selectedSession === $sessionNo;
                        $startsAt = (string) ($sessionOption['starts_at'] ?? '');
                        $sessionYmd = $startsAt !== '' ? \Illuminate\Support\Carbon::parse($startsAt)->format('Y-m-d') : '';
                        $sessionDmy = $startsAt !== '' ? \Illuminate\Support\Carbon::parse($startsAt)->format('d/m/Y') : '';
                    @endphp
                    <label class="bns-intro-session-modal__option bns-intro-session-modal__option--selectable{{ $isChecked ? ' is-selected' : '' }}" for="{{ $optionId }}">
                        <input
                            type="radio"
                            class="bns-intro-session-modal__option-input js-intro-session-number"
                            name="intro_session_number"
                            id="{{ $optionId }}"
                            value="{{ $sessionNo }}"
                            data-session-date="{{ $sessionYmd }}"
                            data-session-date-dmy="{{ $sessionDmy }}"
                            @checked($isChecked)
                        >
                        <span class="bns-intro-session-modal__option-badge">Session {{ $sessionNo }}</span>
                        @if(!empty($sessionOption['date']))
                            <p class="bns-intro-session-modal__option-date">
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                <span>{{ $sessionOption['date'] }}</span>
                            </p>
                        @endif
                        @if(!empty($sessionOption['time']))
                            <p class="bns-intro-session-modal__option-time">
                                <i class="fas fa-clock" aria-hidden="true"></i>
                                <span>{{ $sessionOption['time'] }}</span>
                            </p>
                        @endif
                    </label>
                @endforeach
            </div>
            @error('intro_session_number')
                <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
            @enderror
        @else
            <div class="bns-intro-session-modal__options">
                @foreach($sessions as $sessionOption)
                    <div class="bns-intro-session-modal__option">
                        <span class="bns-intro-session-modal__option-badge">Session {{ $sessionOption['session_number'] ?? '' }}</span>
                        @if(!empty($sessionOption['date']))
                            <p class="bns-intro-session-modal__option-date">
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                <span>{{ $sessionOption['date'] }}</span>
                            </p>
                        @endif
                        @if(!empty($sessionOption['time']))
                            <p class="bns-intro-session-modal__option-time">
                                <i class="fas fa-clock" aria-hidden="true"></i>
                                <span>{{ $sessionOption['time'] }}</span>
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $mapsUrl = $event['maps_url'] ?? ($sessions[0]['maps_url'] ?? null);
            $locationFull = $event['location_full'] ?? ($sessions[0]['location_full'] ?? null);
            $venue = $event['venue'] ?? ($sessions[0]['venue'] ?? null);
        @endphp

        @if(!empty($locationFull) || !empty($venue))
            <ul class="bns-intro-session-modal__event-meta list-unstyled">
                <li>
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <span>{{ $locationFull ?: $venue }}</span>
                </li>
            </ul>
        @endif

        @if(!empty($mapsUrl))
            <a
                href="{{ $mapsUrl }}"
                class="bns-intro-session-modal__event-map"
                target="_blank"
                rel="noopener noreferrer"
            >
                <i class="fas fa-location-arrow" aria-hidden="true"></i> Open GPS Location in Google Maps
            </a>
        @endif
    </div>
@elseif(!empty($event))
    @php
        $singleSessionNo = (int) ($event['session_number'] ?? ($defaultSession ?: 0));
    @endphp
    @if($selectable && $singleSessionNo > 0)
        <input type="hidden" name="intro_session_number" value="{{ $singleSessionNo }}">
    @endif
    <div class="bns-intro-session-modal__event">
        <p class="bns-intro-session-modal__event-label">Upcoming Session</p>
        <h6 class="bns-intro-session-modal__event-title">{{ $event['title'] ?? 'Introduction Session 1' }}</h6>

        <ul class="bns-intro-session-modal__event-meta list-unstyled">
            @if(!empty($event['date']))
                <li>
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    <span>{{ $event['date'] }}</span>
                </li>
            @endif
            @if(!empty($event['time']))
                <li>
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <span>{{ $event['time'] }}</span>
                </li>
            @endif
            @if(!empty($event['location_full']))
                <li>
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <span>{{ $event['location_full'] }}</span>
                </li>
            @elseif(!empty($event['venue']))
                <li>
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <span>{{ $event['venue'] }}</span>
                </li>
            @endif
        </ul>

        @if(!empty($event['maps_url']))
            <a
                href="{{ $event['maps_url'] }}"
                class="bns-intro-session-modal__event-map"
                target="_blank"
                rel="noopener noreferrer"
            >
                <i class="fas fa-location-arrow" aria-hidden="true"></i> Open GPS Location in Google Maps
            </a>
        @endif
    </div>
@endif
