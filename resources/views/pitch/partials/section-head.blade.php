@php($number = $number ?? null)
@php($title = $title ?? '')
@php($icon = $icon ?? 'fa-star')
<div class="bns-pitch-detail__section-head">
    @if($number)
        <span class="bns-pitch-detail__section-num">{{ $number }}</span>
    @endif
    <span class="bns-pitch-detail__section-icon" aria-hidden="true">
        <i class="fas {{ $icon }}"></i>
    </span>
    <h3 class="bns-pitch-detail__section-title">{{ $title }}</h3>
</div>
