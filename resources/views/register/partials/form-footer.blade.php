<div class="bns-admission-form__footer">
    <p class="bns-admission-form__tagline">Business Navachar School (BNS)</p>
    @if(!empty($motto))
        <p class="bns-admission-form__motto">{{ $motto }}</p>
    @endif
    <button type="submit" id="{{ $submitId }}" class="thm-btn bns-admission-form__btn bns-admission-form__btn--apply bns-admission-form__btn--primary">
        Submit Application <i class="fas fa-paper-plane"></i>
    </button>
    <div class="bns-admission-form__footer-links">
        <a href="{{ url('/contact') }}?subject=Schedule%20Counseling"><i class="fas fa-calendar-alt"></i> Counseling</a>
        @if(!empty($siteHeader['phone']))
        <a href="{{ $siteHeader['phone_href'] }}"><i class="fas fa-phone"></i> {{ $siteHeader['phone'] }}</a>
        @endif
    </div>
</div>
