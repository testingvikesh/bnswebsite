@php
    $table = $table ?? [];
    $headers = $table['headers'] ?? [];
    $rows = $table['rows'] ?? [];
    $iconFirst = ! empty($table['icon_first']);
@endphp
@if(!empty($rows))
    <div class="bns-pitch-detail__table-wrap">
        <table class="bns-pitch-detail__table{{ $iconFirst ? ' bns-pitch-detail__table--icon-first' : '' }}">
            @if(!empty($headers))
                <thead>
                    <tr>
                        @foreach($headers as $header)
                            <th>{!! bns_rich_text($header) !!}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        @foreach($row as $cell)
                            @php($isFeeAmount = str_contains((string) $cell, '₹') || preg_match('/\bGST\b/i', (string) $cell))
                            <td>
                                @if($isFeeAmount)
                                    @include('pitch.partials.fee-reveal', ['amount' => $cell])
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
@endif
