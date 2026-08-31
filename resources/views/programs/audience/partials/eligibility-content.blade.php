@php($elig = $program['eligibility_content'] ?? [])
<div class="bns-eligibility-content">
    @if(!empty($elig['programs']) || !empty($elig['tracks']))
        @include('admission.partials.eligibility-content', ['eligibility' => $elig, 'hide_eligibility_cta' => true])
    @else
        @include('programs.audience.partials.eligibility-school-body', ['eligibility' => $elig])
    @endif
</div>
