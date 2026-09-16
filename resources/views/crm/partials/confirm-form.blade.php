@php
    $item = $inquiry ?? $item ?? null;
    $confirmed = $item ? \App\Support\CrmLeadStatus::isConfirmed($item) : false;
@endphp
@if($item && ! $confirmed)
    <form method="POST" action="{{ route('crm.admissions.confirm', $item) }}" class="bns-crm-confirm-form" onsubmit="return confirm('Confirm admission? This member will be removed from the call list.');">
        @csrf
        <button type="submit" class="bns-crm-mini-btn bns-crm-mini-btn--confirm">Confirm Admission</button>
    </form>
@elseif($confirmed)
    <span class="bns-crm-badge bns-crm-badge--admitted">Admission confirmed</span>
@endif
