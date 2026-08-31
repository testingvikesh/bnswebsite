@php($schoolTypes = $schoolTypes ?? config('business_school_types', []))
@php($wrapperClass = $wrapperClass ?? 'bns-school-types')
@if(!empty($schoolTypes['rows']))
    <div class="{{ $wrapperClass }}">
        @if(!empty($showTitle))
            <h3 class="{{ $wrapperClass }}__title">{{ $schoolTypes['title'] ?? 'Four Types of Business Schools' }}</h3>
        @endif
        @if(!empty($schoolTypes['intro']))
            <p class="{{ $wrapperClass }}__intro">
                <i class="fas fa-star" aria-hidden="true"></i>
                {!! bns_rich_text($schoolTypes['intro']) !!}
            </p>
        @endif
        <div class="{{ $wrapperClass }}__table-wrap">
            <table class="{{ $wrapperClass }}__table">
                <thead>
                    <tr>
                        @foreach($schoolTypes['headers'] ?? [] as $index => $header)
                            <th @if($index > 0) class="{{ $wrapperClass }}__tier-head {{ $wrapperClass }}__tier-head--{{ $index }}" @endif>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($schoolTypes['rows'] as $row)
                        <tr>
                            @foreach($row as $index => $cell)
                                <td @if($index === 0) class="{{ $wrapperClass }}__feature-cell" @endif>
                                    @if($index === 0)
                                        <i class="fas fa-star {{ $wrapperClass }}__cell-star" aria-hidden="true"></i>
                                    @endif
                                    {!! bns_rich_text($cell) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
