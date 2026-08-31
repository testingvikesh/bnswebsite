@php($elig = $eligibility ?? [])
@if(!empty($elig['intro']))
    <p class="bns-eligibility-content__intro">{!! bns_rich_text($elig['intro']) !!}</p>
@endif

@if(!empty($elig['age_standards']))
    <div class="bns-eligibility-age">
        <h5 class="bns-eligibility-age__heading">
            <span class="bns-eligibility-age__num">1</span>
            {{ $elig['age_heading'] ?? 'Standard-wise Age Eligibility' }}
        </h5>
        <div class="bns-eligibility-age__grid">
            @foreach($elig['age_standards'] as $row)
                <div class="bns-eligibility-age__item">
                    <span class="bns-eligibility-age__std">{{ $row['standard'] ?? '' }}</span>
                    <span class="bns-eligibility-age__years">{{ $row['age'] ?? '' }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(!empty($elig['criteria']))
    <ol class="bns-eligibility-criteria list-unstyled" start="2">
        @foreach($elig['criteria'] as $index => $criterion)
            <li class="bns-eligibility-criteria__item">
                <span class="bns-eligibility-criteria__icon" aria-hidden="true">
                    <i class="{{ $criterion['icon'] ?? 'fas fa-check-circle' }}"></i>
                </span>
                <div class="bns-eligibility-criteria__body">
                    <span class="bns-eligibility-criteria__num">{{ $index + 2 }}</span>
                    <p class="bns-eligibility-criteria__text">{!! bns_rich_text($criterion['text'] ?? '') !!}</p>
                </div>
            </li>
        @endforeach
    </ol>
@endif

@if(!empty($elig['general_eligibility']))
    <section class="bns-eligibility-section">
        <h3>{{ $elig['general_eligibility']['title'] ?? 'General Eligibility' }}</h3>
        <ul class="bns-admission-list bns-admission-list--checks list-unstyled">
            @foreach($elig['general_eligibility']['items'] ?? [] as $item)
                <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
            @endforeach
        </ul>
    </section>
@endif

@if(!empty($elig['taglines']))
    <div class="bns-eligibility-taglines">
        @foreach($elig['taglines'] as $tagline)
            <p class="bns-eligibility-tagline">{!! bns_rich_text($tagline) !!}</p>
        @endforeach
    </div>
@endif
