@php
    $sessionNo = (int) $sessionNo;
    $isActivePanel = (int) $activeSession === $sessionNo;
    $event = $sessionEvents[$sessionNo] ?? null;
    $rows = $sessionGroups[$sessionNo] ?? collect();
    $introCount = (int) ($stats['session_'.$sessionNo.'_intro'] ?? 0);
    $inquiryCount = (int) ($stats['session_'.$sessionNo.'_inquiry'] ?? 0);
    $confirmCount = (int) ($stats['session_'.$sessionNo.'_confirm'] ?? 0);
    $sessionCount = (int) ($stats['session_'.$sessionNo] ?? 0);
    $todayCount = (int) ($stats['session_'.$sessionNo.'_today'] ?? 0);
    $session1AbsentCount = (int) ($stats['session_1_absent'] ?? 0);
    $session1AttendedCount = (int) ($stats['session_1_attended'] ?? 0);
    $session1RegisteredCount = (int) ($stats['session_1_registered'] ?? 0);
    $session2UniqueCount = (int) ($stats['session_2'] ?? 0);
    $session2CombinedTotal = $session2UniqueCount + $session1AbsentCount;

    $boxBaseQuery = array_filter([
        'session' => $sessionNo,
        'q' => $search !== '' ? $search : null,
        'category' => $categoryFilter !== '' ? $categoryFilter : null,
        'program' => $programFilter !== '' ? $programFilter : null,
    ], fn ($v) => $v !== null && $v !== '');

    $listHash = '#reporting-records-'.$sessionNo;

    $boxUrls = [
        'unique' => route('reporting.index', $boxBaseQuery).$listHash,
        'today' => route('reporting.index', array_merge($boxBaseQuery, [
            'date_from' => $todayDate,
            'date_to' => $todayDate,
        ])).$listHash,
        'intro' => route('reporting.index', array_merge($boxBaseQuery, [
            'form_source' => 'intro-session-modal',
        ])).$listHash,
        'inquiry' => route('reporting.index', array_merge($boxBaseQuery, [
            'form_source' => 'inquiry-modal',
        ])).$listHash,
        'confirm' => route('reporting.index', array_merge($boxBaseQuery, [
            'form_source' => 'register-quick-modal',
        ])).$listHash,
    ];
@endphp

<div
    class="bns-reporting-session-panel"
    data-session-panel="{{ $sessionNo }}"
    @if(! $isActivePanel) hidden @endif
>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ $boxUrls['unique'] }}" class="bns-reporting-stat bns-reporting-stat--total{{ $isUniqueActive ? ' is-active' : '' }}" title="Show all unique mobiles for this session">
                <div class="bns-reporting-stat__icon"><i class="bi bi-phone-fill"></i></div>
                <div class="bns-reporting-stat__label">Unique Mobiles</div>
                <div class="bns-reporting-stat__value">{{ number_format($sessionCount) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ $boxUrls['today'] }}" class="bns-reporting-stat bns-reporting-stat--today{{ $isTodayActive ? ' is-active' : '' }}" title="Filter today's registrations">
                <div class="bns-reporting-stat__icon"><i class="bi bi-calendar-check-fill"></i></div>
                <div class="bns-reporting-stat__label">Today</div>
                <div class="bns-reporting-stat__value">{{ number_format($todayCount) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ $boxUrls['intro'] }}" class="bns-reporting-stat bns-reporting-stat--intro{{ $formSourceFilter === 'intro-session-modal' ? ' is-active' : '' }}" title="Filter Introduction Session only">
                <div class="bns-reporting-stat__icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div class="bns-reporting-stat__label">Intro Session</div>
                <div class="bns-reporting-stat__value">{{ number_format($introCount) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ $boxUrls['inquiry'] }}" class="bns-reporting-stat bns-reporting-stat--inquiry{{ $formSourceFilter === 'inquiry-modal' ? ' is-active' : '' }}" title="Filter Inquiry only">
                <div class="bns-reporting-stat__icon"><i class="bi bi-chat-left-text-fill"></i></div>
                <div class="bns-reporting-stat__label">Inquiry</div>
                <div class="bns-reporting-stat__value">{{ number_format($inquiryCount) }}</div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="{{ $boxUrls['confirm'] }}" class="bns-reporting-stat bns-reporting-stat--quick{{ $formSourceFilter === 'register-quick-modal' ? ' is-active' : '' }}" title="Filter Confirm Admission only">
                <div class="bns-reporting-stat__icon"><i class="bi bi-lightning-charge-fill"></i></div>
                <div class="bns-reporting-stat__label">Confirm Admission</div>
                <div class="bns-reporting-stat__value">{{ number_format($confirmCount) }}</div>
            </a>
        </div>
        @if($sessionNo === 2)
        <div class="col-6 col-md-4 col-xl">
            <a href="#session1-absent" class="bns-reporting-stat bns-reporting-stat--missing" title="Jump to Session 1 Absent list">
                <div class="bns-reporting-stat__icon"><i class="bi bi-person-x-fill"></i></div>
                <div class="bns-reporting-stat__label">Session 1 Absent</div>
                <div class="bns-reporting-stat__value">{{ number_format($session1AbsentCount) }}</div>
            </a>
        </div>
        @endif
    </div>

    <section class="bns-reporting-session mb-4" id="session-{{ $sessionNo }}">
        <div class="bns-reporting-session__title bns-reporting-session__title--{{ $sessionNo }}">
            <div>
                <span class="bns-reporting-session__eyebrow">Introduction Session</span>
                <h2>Session {{ $sessionNo }}</h2>
                @if(!empty($event))
                    <p>{{ $event['date'] ?? '' }}@if(!empty($event['time'])) · {{ $event['time'] }}@endif</p>
                @endif
            </div>
            <div class="bns-reporting-session__count">
                @if($sessionNo === 2)
                    <span class="bns-reporting-session__count-value">{{ number_format($session2UniqueCount) }} + {{ number_format($session1AbsentCount) }} = {{ number_format($session2CombinedTotal) }}</span>
                    <span class="bns-reporting-session__count-label">Session 2 + Session 1 Absent</span>
                @else
                    <span class="bns-reporting-session__count-value">{{ number_format($sessionCount) }}</span>
                    <span class="bns-reporting-session__count-label">Unique Mobiles</span>
                @endif
            </div>
        </div>

        <div class="bns-reporting-table-card" id="reporting-records-{{ $sessionNo }}">
            <div class="bns-reporting-table-card__head">
                <div>
                    <h3><i class="bi bi-table me-1"></i> Session {{ $sessionNo }} Unique Mobile Records</h3>
                    <span>
                        Showing {{ $rows->count() }} unique mobile {{ Str::plural('record', $rows->count()) }}
                        @if(!empty($isTodayDefault))
                            for {{ now()->format('d M Y') }} (IST)
                        @elseif(!empty($hasDateFilter))
                            for selected date range
                        @endif
                    </span>
                </div>
            </div>
            @include('reporting.partials.records-table', ['rows' => $rows, 'sourceBadgeClass' => $sourceBadgeClass])
        </div>
    </section>

    @if($sessionNo === 2)
        <section class="bns-reporting-session mb-4" id="session1-absent">
            <div class="bns-reporting-session__title bns-reporting-session__title--missing">
                <div>
                    <span class="bns-reporting-session__eyebrow">Session 1 Attendance Gap</span>
                    <h2>Session 1 Absent</h2>
                    <p>
                        Registered {{ number_format($session1RegisteredCount) }}
                        · Attended {{ number_format($session1AttendedCount) }}
                        · Absent {{ number_format($session1AbsentCount) }}
                    </p>
                </div>
                <div class="bns-reporting-session__count">
                    <span class="bns-reporting-session__count-value">{{ number_format($session1AbsentCount) }}</span>
                    <span class="bns-reporting-session__count-label">Absent</span>
                </div>
            </div>

            <div class="bns-reporting-table-card">
                <div class="bns-reporting-table-card__head">
                    <div>
                        <h3><i class="bi bi-person-x me-1"></i> Session 1 Absent Users</h3>
                        <span>Showing {{ $session1Absent->count() }} unique {{ Str::plural('user', $session1Absent->count()) }} registered for Session 1 who have not marked attendance</span>
                    </div>
                </div>
                @include('reporting.partials.records-table', ['rows' => $session1Absent, 'sourceBadgeClass' => $sourceBadgeClass])
            </div>
        </section>
    @endif

    @if($sessionNo === 1)
        @php
            $rowsOther = $sessionGroups[0] ?? collect();
        @endphp
        @if($rowsOther->isNotEmpty())
            <section class="bns-reporting-session mb-4">
                <div class="bns-reporting-session__title bns-reporting-session__title--other">
                    <div>
                        <span class="bns-reporting-session__eyebrow">Other Forms</span>
                        <h2>Contact Page & Other</h2>
                        <p>Records not assigned to an introduction session (for example contact page).</p>
                    </div>
                    <div class="bns-reporting-session__count">
                        <span class="bns-reporting-session__count-value">{{ number_format($rowsOther->count()) }}</span>
                        <span class="bns-reporting-session__count-label">Unique Mobiles</span>
                    </div>
                </div>

                <div class="bns-reporting-table-card">
                    <div class="bns-reporting-table-card__head">
                        <div>
                            <h3><i class="bi bi-table me-1"></i> Other Unique Mobile Records</h3>
                            <span>Showing {{ $rowsOther->count() }} unique mobile {{ Str::plural('record', $rowsOther->count()) }}</span>
                        </div>
                    </div>
                    @include('reporting.partials.records-table', ['rows' => $rowsOther, 'sourceBadgeClass' => $sourceBadgeClass])
                </div>
            </section>
        @endif
    @endif
</div>
