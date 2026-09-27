@if(filled(config('services.recaptcha.site_key')))
    <input type="hidden" name="bns_security" value="" data-recaptcha-site-key="{{ config('services.recaptcha.site_key') }}">
    <input type="hidden" name="recaptcha_token" value="">
    <p class="bns-recaptcha-note">
        This site is protected by reCAPTCHA and the Google
        <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Privacy Policy</a>
        and
        <a href="https://policies.google.com/terms" target="_blank" rel="noopener">Terms of Service</a>
        apply.
    </p>
@endif
