@php

    $name = $sponsor['name'] ?? '';

    $designation = trim((string) ($sponsor['designation'] ?? ''));

    $profile = trim((string) ($sponsor['profile'] ?? ''));

    $photoUrl = ! empty($sponsor['photo']) ? bns_vasset($sponsor['photo']) : null;

@endphp



<article class="bns-sponsor-card wow fadeInUp" data-wow-duration="0.85s">

    <div class="bns-sponsor-card__photo-wrap">

        @if($photoUrl)

            <img src="{{ $photoUrl }}" alt="{{ $name }}" class="bns-sponsor-card__photo" loading="lazy" decoding="async">

        @else

            <div class="bns-sponsor-card__photo bns-sponsor-card__photo--placeholder" aria-hidden="true">

                <i class="fas fa-user-tie"></i>

            </div>

        @endif

    </div>



    <div class="bns-sponsor-card__body">

        <h3 class="bns-sponsor-card__name">{{ $name }}</h3>

        @if($designation !== '')

            <p class="bns-sponsor-card__designation">{{ $designation }}</p>

        @endif

        @if($profile !== '')

            <p class="bns-sponsor-card__profile">{!! bns_rich_text($profile) !!}</p>

        @endif

    </div>

</article>

