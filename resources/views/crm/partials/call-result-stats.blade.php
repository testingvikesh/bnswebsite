@php
    $routeName = $routeName ?? 'crm.special-calling';
    $resultKeep = $resultKeep ?? [];
    $roundKeep = $roundKeep ?? [];
    $callStatus = $callStatus ?? '';
    $callRound = (int) ($callRound ?? 0);
    $followupStatusOptions = $followupStatusOptions ?? \App\Models\CrmFollowup::statusOptions();
    $followupNumbers = $followupNumbers ?? \App\Models\CrmFollowup::numbers();
    $callStatusCounts = $callStatusCounts ?? [];
    $callRoundCounts = $callRoundCounts ?? array_fill_keys($followupNumbers, 0);
    $callAverage = $callAverage ?? 0;
    $positiveStatuses = ['admitted', 'interested', 'interested_not_available'];
@endphp

<h3 class="bns-crm-section-title">Calling result wise count</h3>
<div class="bns-crm-stats">
    <a href="{{ route($routeName, $resultKeep) }}" class="bns-crm-stat{{ $callStatus === '' ? ' is-active' : '' }}">
        <span>All results</span>
        <strong>{{ number_format(array_sum($callStatusCounts)) }}</strong>
    </a>
    @foreach($followupStatusOptions as $value => $label)
        <a
            href="{{ route($routeName, array_filter($resultKeep + ['call_status' => $value])) }}"
            class="bns-crm-stat{{ $callStatus === $value ? ' is-active' : '' }}{{ $value === 'pending' ? ' bns-crm-stat--spot' : (in_array($value, $positiveStatuses, true) ? ' bns-crm-stat--present' : '') }}"
        >
            <span>{{ $label }}</span>
            <strong>{{ number_format($callStatusCounts[$value] ?? 0) }}</strong>
        </a>
    @endforeach
</div>

<h3 class="bns-crm-section-title">1st · 2nd · 3rd · 4th · 5th call</h3>
<div class="bns-crm-stats">
    <a href="{{ route($routeName, $roundKeep) }}" class="bns-crm-stat{{ $callRound === 0 ? ' is-active' : '' }}">
        <span>All calls</span>
        <strong>{{ number_format(array_sum($callRoundCounts)) }}</strong>
    </a>
    @foreach($followupNumbers as $roundNo)
        <a
            href="{{ route($routeName, array_filter($roundKeep + ['call_round' => $roundNo])) }}"
            class="bns-crm-stat{{ $callRound === (int) $roundNo ? ' is-active' : '' }}{{ $roundNo === 1 ? ' bns-crm-stat--present' : ($roundNo === count($followupNumbers) ? ' bns-crm-stat--spot' : '') }}"
        >
            <span>{{ \App\Models\CrmFollowup::ordinalLabel((int) $roundNo) }}</span>
            <strong>{{ number_format($callRoundCounts[$roundNo] ?? 0) }}</strong>
        </a>
    @endforeach
    <div class="bns-crm-stat bns-crm-stat--present" title="Average completed follow-ups per assigned student">
        <span>Average</span>
        <strong>{{ number_format((float) $callAverage, 1) }}</strong>
    </div>
</div>
<p class="bns-crm-section-copy">Result cards use the latest call status. Call cards = how many completed that follow-up. Average = completed follow-ups ÷ assigned students. Click a card to filter the list.</p>
