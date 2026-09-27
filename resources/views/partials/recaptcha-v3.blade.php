@if(filled(config('services.recaptcha.site_key')))
    <input type="hidden" name="g-recaptcha-response" value="">
    <p class="bns-recaptcha-note">
        This site is protected by reCAPTCHA and the Google
        <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Privacy Policy</a>
        and
        <a href="https://policies.google.com/terms" target="_blank" rel="noopener">Terms of Service</a>
        apply.
    </p>
@endif
