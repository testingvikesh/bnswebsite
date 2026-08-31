@php($pitch = $pitch ?? [])
@php($sectionNumber = $sectionNumber ?? 15)
@php($schoolTypes = config('business_school_types', []))

@if(!empty($pitch['learning_material']['blocks']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="learning-material">
        @include('pitch.partials.section-head', [
            'number' => $sectionNumber,
            'title' => $pitch['learning_material']['title'] ?? 'Learning Material',
            'icon' => 'fa-book',
        ])
        @php($sectionNumber++)
        @foreach($pitch['learning_material']['blocks'] as $block)
            @if(!empty($block['label']))
                <p class="bns-pitch-detail__block-label">{{ $block['label'] }}</p>
            @endif
            @if(!empty($block['items']))
                @include('pitch.partials.star-points', [
                    'items' => $block['items'],
                    'class' => 'bns-pitch-detail__points--grid bns-pitch-detail__points--compact',
                ])
            @endif
        @endforeach
    </div>
@endif

@if(!empty($pitch['medium_of_instruction']['value']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="medium-of-instruction">
        @include('pitch.partials.section-head', [
            'number' => $sectionNumber,
            'title' => $pitch['medium_of_instruction']['title'] ?? 'Medium of Instruction',
            'icon' => 'fa-language',
        ])
        @php($sectionNumber++)
        <p class="bns-pitch-detail__info-banner">{!! bns_rich_text($pitch['medium_of_instruction']['value']) !!}</p>
    </div>
@endif

@if(!empty($pitch['batch_size']['value']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="batch-size">
        @include('pitch.partials.section-head', [
            'number' => $sectionNumber,
            'title' => $pitch['batch_size']['title'] ?? 'Batch Size',
            'icon' => 'fa-users',
        ])
        @php($sectionNumber++)
        <p class="bns-pitch-detail__info-banner">{!! bns_rich_text($pitch['batch_size']['value']) !!}</p>
        @if(!empty($pitch['batch_size']['note']))
            <p class="bns-pitch-detail__note bns-pitch-detail__note--boxed">
                <i class="fas fa-star" aria-hidden="true"></i>
                {!! bns_rich_text($pitch['batch_size']['note']) !!}
            </p>
        @endif
    </div>
@endif

@if(!empty($pitch['practical_learning_philosophy']['items']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="practical-learning">
        @include('pitch.partials.section-head', [
            'number' => $sectionNumber,
            'title' => $pitch['practical_learning_philosophy']['title'] ?? 'Practical Learning Philosophy',
            'icon' => 'fa-lightbulb',
        ])
        @php($sectionNumber++)
        @include('pitch.partials.star-points', [
            'items' => $pitch['practical_learning_philosophy']['items'],
            'class' => 'bns-pitch-detail__points--grid',
        ])
    </div>
@endif

@if(!empty($schoolTypes['rows']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="fees-structure">
        @include('pitch.partials.section-head', [
            'number' => $sectionNumber,
            'title' => 'Fees Structure',
            'icon' => 'fa-gem',
        ])
        @php($sectionNumber++)
        @include('pitch.partials.school-types-table', [
            'schoolTypes' => $schoolTypes,
            'wrapperClass' => 'bns-pitch-detail__school-types',
            'showTitle' => false,
        ])
    </div>
@endif

@if(!empty($pitch['join_bns']['text']))
    <div class="bns-pitch-detail__section bns-pitch-detail__section--closing wow fadeInUp" data-wow-duration="0.85s" id="join-bns">
        @include('pitch.partials.section-head', [
            'number' => $sectionNumber,
            'title' => $pitch['join_bns']['title'] ?? "Join India's First Weekly Business School",
            'icon' => 'fa-graduation-cap',
        ])
        <p class="bns-pitch-detail__closing-text">{!! bns_rich_text($pitch['join_bns']['text']) !!}</p>
    </div>
@endif
