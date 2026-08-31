@extends('layouts.front')

@section('title', $page['meta_title'] ?? ($page['page_title'] ?? 'Dr. Mehul Rupani'))

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/about-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/team.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/expert-mehul.css') }}" />
@endpush

@section('content')
@php
    $identity = $expert['identity'] ?? [];
    $creed = $expert['creed'] ?? [];
    $motto = $expert['motto'] ?? [];
    $businessMotto = $expert['business_motto'] ?? [];
    $photo = !empty($expert['photo']) ? bns_vasset($expert['photo']) : null;
@endphp

<div class="bns-about-page bns-expert-page bns-expert-mehul">
    @include('partials.page-header', [
        'title' => $page['page_title'] ?? 'Dr. Mehul Rupani',
        'subtitle' => $page['page_subtitle'] ?? null,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title'] ?? 'Expert'],
            ['label' => $identity['name'] ?? 'Dr. Mehul Rupani'],
        ],
    ])

    <section class="bns-vision-content">
        <div class="container">
            <div class="bns-vision-card bns-expert-mehul__card">
                <div class="bns-expert-mehul__identity wow fadeInUp" data-wow-duration="0.85s">
                    @if($photo)
                        <div class="bns-expert-mehul__photo-wrap">
                            <img
                                src="{{ $photo }}"
                                alt="{{ $identity['name'] ?? 'Dr. Mehul Rupani' }}"
                                class="bns-expert-mehul__photo"
                                loading="eager"
                                decoding="async"
                            >
                        </div>
                    @endif
                    <div class="bns-expert-mehul__identity-copy">
                        @if(!empty($identity['credentials']))
                            <p class="bns-expert-mehul__credentials">{{ $identity['credentials'] }}</p>
                        @endif
                        <h2 class="bns-expert-mehul__name">{{ $identity['name'] ?? '' }}</h2>
                        @if(!empty($identity['designation']))
                            <p class="bns-expert-mehul__designation">{{ $identity['designation'] }}</p>
                        @endif
                        @if(!empty($identity['organization']))
                            <p class="bns-expert-mehul__org">{{ $identity['organization'] }}</p>
                        @endif
                        @if(!empty($identity['tagline']))
                            <p class="bns-expert-mehul__tagline">{!! bns_rich_text($identity['tagline']) !!}</p>
                        @endif
                    </div>
                </div>

                @if(!empty($motto['gujarati_lines']))
                    <section class="bns-expert-mehul__motto wow fadeInUp" data-wow-duration="0.85s" data-wow-delay="0.05s">
                        @if(!empty($motto['label']))
                            <span class="bns-expert-mehul__motto-label">{{ $motto['label'] }}</span>
                        @endif
                        <div class="bns-expert-mehul__motto-gujarati" lang="gu">
                            @foreach($motto['gujarati_lines'] as $line)
                                <p class="bns-expert-mehul__motto-line{{ mb_strlen(trim($line)) <= 4 ? ' is-connector' : '' }}">
                                    {!! bns_rich_text($line) !!}
                                </p>
                            @endforeach
                        </div>
                        @if(!empty($motto['english']))
                            <p class="bns-expert-mehul__motto-english">{!! bns_rich_text($motto['english']) !!}</p>
                        @endif
                    </section>
                @endif

                @if(!empty($businessMotto['lines']))
                    <section class="bns-expert-mehul__business-motto wow fadeInUp" data-wow-duration="0.85s" data-wow-delay="0.08s">
                        @if(!empty($businessMotto['label']))
                            <span class="bns-expert-mehul__motto-label">{{ $businessMotto['label'] }}</span>
                        @endif
                        <div class="bns-expert-mehul__business-motto-lines">
                            @foreach($businessMotto['lines'] as $line)
                                <p class="bns-expert-mehul__business-motto-line{{ $loop->first ? ' is-lead' : '' }}">
                                    {!! bns_rich_text($line) !!}
                                </p>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if(!empty($creed['lines']))
                    <section class="bns-expert-mehul__creed wow fadeInUp" data-wow-duration="0.9s" data-wow-delay="0.1s">
                        @if(!empty($creed['label']))
                            <span class="bns-expert-mehul__creed-label">{{ $creed['label'] }}</span>
                        @endif
                        @if(!empty($creed['title']))
                            <h3 class="bns-expert-mehul__creed-title">{{ $creed['title'] }}</h3>
                        @endif
                        @if(!empty($creed['intro']))
                            <p class="bns-expert-mehul__creed-intro">{!! bns_rich_text($creed['intro']) !!}</p>
                        @endif
                        <ol class="bns-expert-mehul__creed-list list-unstyled">
                            @foreach($creed['lines'] as $line)
                                <li class="bns-expert-mehul__creed-line">
                                    <span class="bns-expert-mehul__creed-mark" aria-hidden="true"></span>
                                    <span>{!! bns_rich_text($line) !!}</span>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                @include('expert.partials.framework-table', [
                    'framework' => $expert['business_framework'] ?? [],
                ])

                @include('expert.partials.framework-table', [
                    'framework' => $expert['dhandho_framework'] ?? [],
                ])

                @php($signature = $expert['signature_thought'] ?? [])
                @if(!empty($signature['lines']))
                    <section class="bns-expert-mehul__signature wow fadeInUp" data-wow-duration="0.85s">
                        @if(!empty($signature['label']))
                            <span class="bns-expert-mehul__motto-label">{{ $signature['label'] }}</span>
                        @endif
                        @if(!empty($signature['title']))
                            <h3 class="bns-expert-mehul__signature-title">{!! bns_rich_text($signature['title']) !!}</h3>
                        @endif
                        <ul class="bns-expert-mehul__signature-list list-unstyled">
                            @foreach($signature['lines'] as $line)
                                <li>{!! bns_rich_text($line) !!}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @php($formula = $expert['final_formula'] ?? [])
                @if(!empty($formula['items']))
                    <section class="bns-expert-mehul__formula wow fadeInUp" data-wow-duration="0.85s">
                        @if(!empty($formula['label']))
                            <span class="bns-expert-mehul__creed-label">{{ $formula['label'] }}</span>
                        @endif
                        <div class="bns-expert-mehul__formula-items">
                            @foreach($formula['items'] as $item)
                                <article class="bns-expert-mehul__formula-item">
                                    <p class="bns-expert-mehul__formula-eq">{!! bns_rich_text($item['equation'] ?? '') !!}</p>
                                    <p class="bns-expert-mehul__formula-result">
                                        <span aria-hidden="true">=</span>
                                        {!! bns_rich_text($item['result'] ?? '') !!}
                                    </p>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                @include('expert.partials.framework-table', [
                    'framework' => $expert['currency_notes'] ?? [],
                ])

                @include('expert.partials.framework-table', [
                    'framework' => $expert['currency_monuments'] ?? [],
                ])

                @include('expert.partials.framework-table', [
                    'framework' => $expert['currency_facts'] ?? [],
                ])

                @include('expert.partials.framework-table', [
                    'framework' => $expert['currency_languages'] ?? [],
                ])

                @include('expert.partials.framework-table', [
                    'framework' => $expert['currency_business_learning'] ?? [],
                ])

                @php($currencyClosing = $expert['currency_closing'] ?? [])
                @if(!empty($currencyClosing['text']))
                    <section class="bns-expert-mehul__currency-closing wow fadeInUp" data-wow-duration="0.85s">
                        @if(!empty($currencyClosing['label']))
                            <span class="bns-expert-mehul__creed-label">{{ $currencyClosing['label'] }}</span>
                        @endif
                        <p class="bns-expert-mehul__currency-closing-text">
                            <i class="fas fa-quote-left" aria-hidden="true"></i>
                            {!! bns_rich_text($currencyClosing['text']) !!}
                        </p>
                    </section>
                @endif

                @include('expert.partials.framework-table', [
                    'framework' => $expert['currency_innovation'] ?? [],
                ])

                @include('expert.partials.framework-table', [
                    'framework' => $expert['currency_wow'] ?? [],
                ])

                @php($businessClosing = $expert['currency_business_closing'] ?? [])
                @if(!empty($businessClosing['intro']) || !empty($businessClosing['build_items']))
                    <section class="bns-expert-mehul__business-closing wow fadeInUp" data-wow-duration="0.85s">
                        @if(!empty($businessClosing['label']))
                            <span class="bns-expert-mehul__motto-label">{{ $businessClosing['label'] }}</span>
                        @endif
                        @if(!empty($businessClosing['intro']))
                            <p class="bns-expert-mehul__business-closing-intro">{!! bns_rich_text($businessClosing['intro']) !!}</p>
                        @endif
                        @if(!empty($businessClosing['bridge']))
                            <p class="bns-expert-mehul__business-closing-bridge">{!! bns_rich_text($businessClosing['bridge']) !!}</p>
                        @endif
                        @if(!empty($businessClosing['build_items']))
                            @if(!empty($businessClosing['build_title']))
                                <p class="bns-expert-mehul__business-closing-build-title">{!! bns_rich_text($businessClosing['build_title']) !!}</p>
                            @endif
                            <ul class="bns-expert-mehul__business-closing-list list-unstyled">
                                @foreach($businessClosing['build_items'] as $item)
                                    <li>
                                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                                        <span>{!! bns_rich_text($item) !!}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($businessClosing['outro']))
                            <p class="bns-expert-mehul__business-closing-outro">{!! bns_rich_text($businessClosing['outro']) !!}</p>
                        @endif
                    </section>
                @endif

                @php($signatureLine = $expert['currency_signature_line'] ?? [])
                @if(!empty($signatureLine['text']))
                    <section class="bns-expert-mehul__currency-closing wow fadeInUp" data-wow-duration="0.85s">
                        @if(!empty($signatureLine['label']))
                            <span class="bns-expert-mehul__creed-label">{{ $signatureLine['label'] }}</span>
                        @endif
                        <p class="bns-expert-mehul__currency-closing-text">
                            <i class="fas fa-quote-left" aria-hidden="true"></i>
                            {!! bns_rich_text($signatureLine['text']) !!}
                        </p>
                    </section>
                @endif

                @include('expert.partials.framework-table', [
                    'framework' => $expert['income_framework'] ?? [],
                ])

                @include('expert.partials.framework-table', [
                    'framework' => $expert['double_framework'] ?? [],
                ])

                @php($incomeFormula = $expert['income_double_formula'] ?? [])
                @if(!empty($incomeFormula['flow']) || !empty($incomeFormula['results']))
                    <section class="bns-expert-mehul__income-formula wow fadeInUp" data-wow-duration="0.85s">
                        @if(!empty($incomeFormula['label']))
                            <span class="bns-expert-mehul__motto-label">{{ $incomeFormula['label'] }}</span>
                        @endif
                        @if(!empty($incomeFormula['flow']))
                            <div class="bns-expert-mehul__income-flow">
                                @foreach($incomeFormula['flow'] as $step)
                                    <p class="bns-expert-mehul__income-flow-step">{!! bns_rich_text($step) !!}</p>
                                    @if(! $loop->last)
                                        <div class="bns-expert-mehul__income-flow-arrow" aria-hidden="true">
                                            <i class="fas fa-arrow-down"></i>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($incomeFormula['results']))
                            <div class="bns-expert-mehul__income-flow-arrow" aria-hidden="true">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            @if(!empty($incomeFormula['result_title']))
                                <p class="bns-expert-mehul__income-result-title">{{ $incomeFormula['result_title'] }}</p>
                            @endif
                            <ul class="bns-expert-mehul__income-results list-unstyled">
                                @foreach($incomeFormula['results'] as $result)
                                    <li>{!! bns_rich_text($result) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                @endif

                @php($incomeClosing = $expert['income_double_closing'] ?? [])
                @if(!empty($incomeClosing['text']))
                    <section class="bns-expert-mehul__currency-closing wow fadeInUp" data-wow-duration="0.85s">
                        @if(!empty($incomeClosing['label']))
                            <span class="bns-expert-mehul__creed-label">{{ $incomeClosing['label'] }}</span>
                        @endif
                        <p class="bns-expert-mehul__currency-closing-text">
                            <i class="fas fa-quote-left" aria-hidden="true"></i>
                            {!! bns_rich_text($incomeClosing['text']) !!}
                        </p>
                    </section>
                @endif

                @include('expert.partials.framework-table', [
                    'framework' => $expert['balance_sheet_framework'] ?? [],
                ])

                @php($balanceFormula = $expert['balance_sheet_formula'] ?? [])
                @if(!empty($balanceFormula['flow']) || !empty($balanceFormula['result']))
                    <section class="bns-expert-mehul__income-formula wow fadeInUp" data-wow-duration="0.85s">
                        @if(!empty($balanceFormula['label']))
                            <span class="bns-expert-mehul__motto-label">{{ $balanceFormula['label'] }}</span>
                        @endif
                        @if(!empty($balanceFormula['flow']))
                            <p class="bns-expert-mehul__income-flow-step">{!! bns_rich_text($balanceFormula['flow']) !!}</p>
                        @endif
                        @if(!empty($balanceFormula['result']))
                            <div class="bns-expert-mehul__income-flow-arrow" aria-hidden="true">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            @if(!empty($balanceFormula['result_label']))
                                <p class="bns-expert-mehul__income-result-title">{{ $balanceFormula['result_label'] }}</p>
                            @endif
                            <p class="bns-expert-mehul__balance-result">{!! bns_rich_text($balanceFormula['result']) !!}</p>
                        @endif
                    </section>
                @endif

                @include('expert.partials.framework-table', [
                    'framework' => $expert['double_growth_framework'] ?? [],
                ])

                @include('expert.partials.homework', [
                    'homework' => $expert['homework_01'] ?? [],
                ])

                @if(!empty($expert['profile']))
                    <section class="bns-expert-mehul__profile wow fadeInUp" data-wow-duration="0.85s" data-wow-delay="0.15s">
                        <div class="bns-vision-header">
                            <span class="bns-vision-header__label">Profile</span>
                            <h3>About Dr. Mehul Rupani</h3>
                        </div>
                        @include('about.partials.leader-profile-structured', [
                            'profile' => $expert['profile'],
                        ])
                    </section>
                @endif

                @if(!empty($expert['actions']))
                    <div class="bns-vision-actions">
                        @foreach($expert['actions'] as $action)
                            <a href="{{ route($action['route']) }}" class="bns-vision-actions__btn">
                                <i class="{{ $action['icon'] ?? 'fas fa-arrow-right' }}" aria-hidden="true"></i>
                                {{ $action['label'] ?? '' }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
