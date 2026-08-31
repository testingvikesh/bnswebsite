@php
    $data = $faqs ?? config('admission.pages.faqs', []);
    $office = $config['office'] ?? [];
    $sections = [
        ['heading' => null, 'items' => $data['questions'] ?? []],
        ['heading' => $data['additional_heading'] ?? null, 'items' => $data['additional_questions'] ?? []],
        ['heading' => $data['collaboration_heading'] ?? null, 'items' => $data['collaboration_questions'] ?? []],
    ];
@endphp

@foreach($sections as $section)
    @if(!empty($section['items']))
        @if(!empty($section['heading']))
            <h3 class="bns-faq-section-title">{{ $section['heading'] }}</h3>
        @endif
        <div class="bns-faq-list {{ !empty($section['heading']) ? 'bns-faq-list--additional' : '' }}">
            @foreach($section['items'] as $faq)
            <details class="bns-faq-item" @if(($faq['number'] ?? 0) === 1 && empty($section['heading'])) open @endif>
                <summary class="bns-faq-item__question">
                    <span class="bns-faq-item__number">{{ $faq['number'] ?? '' }}</span>
                    <span class="bns-faq-item__text">{{ $faq['question'] ?? '' }}</span>
                    <i class="fas fa-chevron-down bns-faq-item__toggle" aria-hidden="true"></i>
                </summary>
                <div class="bns-faq-item__answer">
                    @if(!empty($faq['answer']))
                        <p>{!! bns_rich_text($faq['answer']) !!}</p>
                    @endif

                    @if(!empty($faq['items']))
                        @php $firstItem = $faq['items'][0] ?? null; @endphp
                        @if(is_array($firstItem) && !empty($firstItem['icon']))
                    <ul class="bns-faq-programs list-unstyled">
                        @foreach($faq['items'] as $item)
                            <li>
                                @if(!empty($item['icon']))<span class="bns-faq-programs__icon">{{ $item['icon'] }}</span>@endif
                                {{ $item['label'] ?? '' }}
                            </li>
                        @endforeach
                    </ul>
                        @else
                    <ul class="bns-admission-list list-unstyled">
                        @foreach($faq['items'] as $item)
                            <li><i class="fas fa-check"></i> {!! bns_rich_text(is_array($item) ? ($item['label'] ?? '') : $item) !!}</li>
                        @endforeach
                    </ul>
                        @endif
                    @endif

                    @if(!empty($faq['outro']))
                        <p class="bns-faq-item__outro">{!! bns_rich_text($faq['outro']) !!}</p>
                    @endif

                    @if(!empty($faq['link']))
                        @php
                            $link = $faq['link'];
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
            </details>
            @endforeach
        </div>
    @endif
@endforeach

@if(!empty($data['still_have_questions']))
<section class="bns-process-section bns-process-section--help bns-faq-help">
    <h3>{{ $data['still_have_questions']['title'] ?? 'Still Have Questions?' }}</h3>
    @if(!empty($data['still_have_questions']['intro']))
        <p class="bns-process-section__intro">{!! bns_rich_text($data['still_have_questions']['intro']) !!}</p>
    @endif
    <div class="bns-process-help">
        @foreach($data['still_have_questions']['contacts'] ?? [] as $contact)
            @php
                $type = $contact['type'] ?? '';
                $href = match($type) {
                    'phone' => 'tel:'.preg_replace('/\D+/', '', $office['phone'] ?? ''),
                    'whatsapp' => 'https://wa.me/'.preg_replace('/\D+/', '', $office['whatsapp'] ?? ''),
                    'email' => 'mailto:'.($office['email'] ?? ''),
                    'apply' => route('register'),
                    'page' => route('admissions.page', $contact['slug'] ?? '#'),
                    default => route('contact'),
                };
            @endphp
            @if($type === 'apply')
                <button type="button" class="bns-process-help__item" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                    <span class="bns-process-help__icon">{{ $contact['icon'] ?? '' }}</span>
                    <span>{{ $contact['label'] ?? '' }}</span>
                </button>
            @else
                <a href="{{ $href }}" class="bns-process-help__item"
                   @if(in_array($type, ['whatsapp'], true)) target="_blank" rel="noopener" @endif>
                    <span class="bns-process-help__icon">{{ $contact['icon'] ?? '' }}</span>
                    <span>{{ $contact['label'] ?? '' }}</span>
                </a>
            @endif
        @endforeach
    </div>
</section>
@endif
