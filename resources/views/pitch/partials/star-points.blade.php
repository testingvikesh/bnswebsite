@php($items = $items ?? [])
@php($class = $class ?? '')
@if(!empty($items))
    <ul class="bns-pitch-detail__points {{ $class }}">
        @foreach($items as $item)
            <li class="bns-pitch-detail__point">
                <span class="bns-pitch-detail__point-icon" aria-hidden="true">
                    <i class="fas fa-star"></i>
                </span>
                <span class="bns-pitch-detail__point-text">{!! bns_point_html_hide_fees($item) !!}</span>
            </li>
        @endforeach
    </ul>
    @include('pitch.partials.fee-reveal-script')
@endif
