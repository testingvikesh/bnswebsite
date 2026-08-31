@php
    $root = $eligibility ?? config('admission.pages.eligibility-criteria', []);
    $sections = !empty($root['tracks']) ? $root['tracks'] : [$root];
    $hasTracks = !empty($root['tracks']);
@endphp

@foreach($sections as $sectionIndex => $data)
    @if($hasTracks)
        <div class="bns-eligibility-track{{ $sectionIndex > 0 ? ' bns-eligibility-track--divider' : '' }}">
            @if(!empty($data['school_name']))
                <h3 class="bns-eligibility-content__school">{{ $data['school_name'] }}</h3>
            @endif
            @if(!empty($data['subtitle']))
                <p class="bns-eligibility-content__school-sub">{!! bns_rich_text($data['subtitle']) !!}</p>
            @endif
    @endif

    @include('admission.partials.eligibility-content-section', [
        'data' => $data,
        'hide_eligibility_cta' => $hide_eligibility_cta ?? false,
    ])

    @if($hasTracks)
        </div>
    @endif
@endforeach
