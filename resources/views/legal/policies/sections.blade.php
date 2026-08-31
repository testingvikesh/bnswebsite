@if(!empty($pageContent['intro']))
    @foreach($pageContent['intro'] as $paragraph)
        <p>{!! bns_rich_text($paragraph) !!}</p>
    @endforeach
@endif

@foreach($pageContent['sections'] ?? [] as $section)
    <hr>
    <h2>{{ $section['title'] }}</h2>
    @foreach($section['paragraphs'] ?? [] as $paragraph)
        <p>{!! bns_rich_text($paragraph) !!}</p>
    @endforeach
    @if(!empty($section['list']))
        <ul>
            @foreach($section['list'] as $item)
                <li>{!! bns_rich_text($item) !!}</li>
            @endforeach
        </ul>
    @endif
    @if(!empty($section['show_contact']))
        <ul class="list-unstyled">
            @include('legal.partials.site-contact', ['phoneLabel' => $section['contact_phone_label'] ?? 'Helpline'])
        </ul>
    @endif
@endforeach

@include('legal.partials.brand-footer')
