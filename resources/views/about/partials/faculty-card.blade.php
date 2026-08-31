@php
    $modalId = 'bnsFacultyModal-'.\Illuminate\Support\Str::slug((string) ($member->full_name ?: 'faculty')).'-'.$index;
@endphp

<div class="col-sm-6 col-lg-4">
    <button
        type="button"
        class="bns-faculty-card"
        data-bs-toggle="modal"
        data-bs-target="#{{ $modalId }}"
    >
        <div class="bns-faculty-card__header">
            <div class="bns-faculty-card__avatar">
                @if($member->photo_url)
                    <img src="{{ $member->photo_url }}" alt="{{ $member->display_name }}" class="bns-faculty-card__photo" loading="lazy" decoding="async">
                @else
                    <div class="bns-faculty-card__photo bns-faculty-card__photo--placeholder" aria-hidden="true">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="bns-faculty-card__body">
            <p class="bns-faculty-card__label">Visiting Expert Faculty</p>
            <h3 class="bns-faculty-card__name">{{ $member->display_name }}</h3>
            <p class="bns-faculty-card__designation">{{ $member->designation }}</p>
            @if($member->recognition)
                <p class="bns-faculty-card__recognition">
                    <i class="fas fa-award" aria-hidden="true"></i>
                    <span>{!! bns_rich_text($member->recognition) !!}</span>
                </p>
            @endif
            @if($member->professional_experience)
                <p class="bns-faculty-card__meta">{{ $member->professional_experience }} Experience</p>
            @endif
            <span class="bns-faculty-card__action">
                View Profile
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </span>
        </div>
    </button>
</div>
