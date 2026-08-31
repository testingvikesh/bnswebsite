@php
    $framework = $framework ?? [];
    $sectionLabel = $framework['label'] ?? 'Framework';
    $letterColumn = array_key_exists('letter_column', $framework)
        ? (bool) $framework['letter_column']
        : true;
@endphp
@if(!empty($framework['rows']))
    <section class="bns-expert-mehul__framework wow fadeInUp" data-wow-duration="0.85s">
        @if(!empty($framework['title']))
            <div class="bns-vision-header bns-expert-mehul__framework-head">
                @if($sectionLabel !== '')
                    <span class="bns-vision-header__label">{{ $sectionLabel }}</span>
                @endif
                <h3>{!! bns_rich_text($framework['title']) !!}</h3>
                @if(!empty($framework['subtitle']))
                    <p class="bns-expert-mehul__framework-subtitle">{!! bns_rich_text($framework['subtitle']) !!}</p>
                @endif
            </div>
        @endif
        @if(!empty($framework['intro']))
            <p class="bns-expert-mehul__framework-intro">{!! bns_rich_text($framework['intro']) !!}</p>
        @endif
        <div class="bns-expert-mehul__table-wrap">
            <table class="bns-expert-mehul__table{{ $letterColumn ? '' : ' bns-expert-mehul__table--plain' }}">
                @if(!empty($framework['headers']))
                    <thead>
                        <tr>
                            @foreach($framework['headers'] as $header)
                                <th scope="col">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                <tbody>
                    @foreach($framework['rows'] as $row)
                        <tr>
                            @foreach($row as $index => $cell)
                                <td class="{{ ($letterColumn && $index === 0) ? 'is-letter' : ($index === 0 ? 'is-feature' : '') }}">
                                    @if($letterColumn && $index === 0)
                                        <span class="bns-expert-mehul__letter">{{ $cell }}</span>
                                    @else
                                        {!! bns_rich_text($cell) !!}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(!empty($framework['notes']))
            <ul class="bns-expert-mehul__framework-notes list-unstyled">
                @foreach($framework['notes'] as $note)
                    <li>{!! bns_rich_text($note) !!}</li>
                @endforeach
            </ul>
        @endif
    </section>
@endif
