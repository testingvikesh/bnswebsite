@php
    $data = $process ?? config('admission.pages.admission-process', []);
    $office = $config['office'] ?? [];
@endphp

@if(!empty($data['steps']))
<div class="bns-process-steps">
    @foreach($data['steps'] as $step)
    <article class="bns-process-step">
        <div class="bns-process-step__marker">
            <span class="bns-process-step__number">{{ $step['number'] ?? '' }}</span>
        </div>
        <div class="bns-process-step__content">
            <h3 class="bns-process-step__title">Step {{ $step['number'] ?? '' }} – {{ $step['title'] ?? '' }}</h3>
            @if(!empty($step['intro']))
                <p class="bns-process-step__intro">{!! bns_rich_text($step['intro']) !!}</p>
            @endif

            @if(!empty($step['programs']))
            <div class="bns-process-programs">
                @foreach($step['programs'] as $program)
                    <a href="{{ route('admissions.page', 'programs') }}" class="bns-process-program">
                        @if(!empty($program['icon']))<span class="bns-process-program__icon">{{ $program['icon'] }}</span>@endif
                        <span>{{ $program['label'] ?? '' }}</span>
                    </a>
                @endforeach
            </div>
            @endif

            @if(!empty($step['note']))
                <p class="bns-process-step__note">{!! bns_rich_text($step['note']) !!}</p>
            @endif

            @if(!empty($step['items']))
            <ul class="bns-admission-list list-unstyled {{ ($step['style'] ?? '') === 'success' ? 'bns-admission-list--checks' : '' }}">
                @foreach($step['items'] as $item)
                    <li>
                        @if(($step['style'] ?? '') === 'success')
                            <i class="fas fa-check-circle"></i>
                        @else
                            <i class="fas fa-check"></i>
                        @endif
                        {!! bns_rich_text($item) !!}
                    </li>
                @endforeach
            </ul>
            @endif

            @if(!empty($step['examples']))
                @if(!empty($step['examples_label']))
                    <p class="bns-process-step__label">{{ $step['examples_label'] }}</p>
                @endif
                <ul class="bns-process-tags list-unstyled">
                    @foreach($step['examples'] as $example)
                        <li><span class="bns-process-tags__icon">{{ $example['icon'] ?? '' }}</span> {{ $example['label'] ?? '' }}</li>
                    @endforeach
                </ul>
            @endif

            @if(!empty($step['options']))
                @if(!empty($step['options_label']))
                    <p class="bns-process-step__label">{{ $step['options_label'] }}</p>
                @endif
                <ul class="bns-process-tags list-unstyled">
                    @foreach($step['options'] as $option)
                        <li><span class="bns-process-tags__icon">{{ $option['icon'] ?? '' }}</span> {{ $option['label'] ?? '' }}</li>
                    @endforeach
                </ul>
            @endif

            @if(!empty($step['link']))
                @php
                    $link = $step['link'];
                    $href = !empty($link['route'])
                        ? route($link['route'])
                        : route('admissions.page', $link['slug'] ?? '#');
                @endphp
                @if(($link['route'] ?? '') === 'register')
                    <button type="button" class="bns-process-step__link" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        {{ $link['label'] ?? 'Learn More' }} <i class="fas fa-arrow-right"></i>
                    </button>
                @else
                    <a href="{{ $href }}" class="bns-process-step__link">
                        {{ $link['label'] ?? 'Learn More' }} <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            @endif
        </div>
    </article>
    @endforeach
</div>
@endif

@if(!empty($data['certification']))
<section class="bns-process-section bns-process-section--cert">
    <h3>{{ $data['certification']['title'] ?? 'Certification' }}</h3>
    @if(!empty($data['certification']['intro']))
        <p class="bns-process-section__intro">{!! bns_rich_text($data['certification']['intro']) !!}</p>
    @endif
    <ul class="bns-process-tags list-unstyled">
        @foreach($data['certification']['items'] ?? [] as $item)
            <li><span class="bns-process-tags__icon">{{ $item['icon'] ?? '' }}</span> {{ $item['label'] ?? '' }}</li>
        @endforeach
    </ul>
    @if(!empty($data['certification']['outro']))
        <p class="bns-process-section__outro">{!! bns_rich_text($data['certification']['outro']) !!}</p>
    @endif
</section>
@endif

@if(!empty($data['need_help']))
<section class="bns-process-section bns-process-section--help">
    <h3>{{ $data['need_help']['title'] ?? 'Need Help?' }}</h3>
    @if(!empty($data['need_help']['intro']))
        <p class="bns-process-section__intro">{!! bns_rich_text($data['need_help']['intro']) !!}</p>
    @endif
    <div class="bns-process-help">
        @foreach($data['need_help']['contacts'] ?? [] as $contact)
            @php
                $type = $contact['type'] ?? '';
                $href = match($type) {
                    'phone' => 'tel:'.preg_replace('/\D+/', '', $office['phone'] ?? ''),
                    'whatsapp' => 'https://wa.me/'.preg_replace('/\D+/', '', $office['whatsapp'] ?? ''),
                    'email' => 'mailto:'.($office['email'] ?? ''),
                    'office' => route('admissions.page', $contact['slug'] ?? 'contact-admission-office'),
                    default => '#',
                };
            @endphp
            <a href="{{ $href }}" class="bns-process-help__item" @if(in_array($type, ['whatsapp'], true)) target="_blank" rel="noopener" @endif>
                <span class="bns-process-help__icon">{{ $contact['icon'] ?? '' }}</span>
                <span>{{ $contact['label'] ?? '' }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

@if(!empty($data['ready']))
<section class="bns-process-section bns-process-section--ready">
    <h3>{{ $data['ready']['title'] ?? 'Ready to Begin?' }}</h3>
    @foreach($data['ready']['paragraphs'] ?? [] as $paragraph)
        <p class="bns-process-section__intro">{!! bns_rich_text($paragraph) !!}</p>
    @endforeach
</section>
@endif

@if(!empty($data['actions']))
<section class="bns-process-actions">
    <h3>Call to Action</h3>
    <div class="bns-admission-ctas__grid">
        @foreach($data['actions'] as $action)
            @php
                $href = !empty($action['route'])
                    ? route($action['route'])
                    : route('admissions.page', $action['slug'] ?? '#');
            @endphp
            @if(($action['route'] ?? '') === 'register')
                <button type="button" class="bns-admission-cta bns-admission-cta--{{ $action['style'] ?? 'primary' }}" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                    <i class="{{ $action['icon'] ?? 'fas fa-arrow-right' }}"></i> {{ $action['label'] ?? '' }}
                </button>
            @else
                <a href="{{ $href }}" class="bns-admission-cta bns-admission-cta--{{ $action['style'] ?? 'primary' }}">
                    <i class="{{ $action['icon'] ?? 'fas fa-arrow-right' }}"></i> {{ $action['label'] ?? '' }}
                </a>
            @endif
        @endforeach
    </div>
</section>
@endif

@if(!empty($data['closing']))
<div class="bns-eligibility-closing bns-process-closing">
    @if(!empty($data['closing']['brand']))
        <p class="bns-admission-tagline__brand">{{ $data['closing']['brand'] }}</p>
    @endif
    @if(!empty($data['closing']['subtitle']))
        <p class="bns-eligibility-closing__subtitle">{!! bns_rich_text($data['closing']['subtitle']) !!}</p>
    @endif
    @if(!empty($data['closing']['tagline']))
        <p class="bns-eligibility-closing__tagline">{!! bns_rich_text($data['closing']['tagline']) !!}</p>
    @endif
    @if(!empty($data['closing']['hindi']))
        <p class="bns-eligibility-closing__hindi">{!! bns_rich_text($data['closing']['hindi']) !!}</p>
    @endif
</div>
@endif
