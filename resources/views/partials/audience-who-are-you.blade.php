@php
    $whoAreYouPrograms = config('register.quick_modal_programs', []);
    $whoAreYouSelected = $whoAreYouSelected ?? old('register_program_choice', '');
@endphp

@if(!empty($whoAreYouPrograms))
    <div class="bns-quick-register-form__who">
        <span class="bns-quick-register-form__who-label">Who are you?</span>
        <div class="bns-quick-register-form__programs" role="radiogroup" aria-label="Choose your BNS program">
            @foreach($whoAreYouPrograms as $program)
                <label class="bns-quick-register-form__program">
                    <input
                        type="radio"
                        name="register_program_choice"
                        value="{{ $program['id'] }}"
                        data-contact-program="{{ $program['contact_program'] }}"
                        data-contact-category="{{ $program['category'] }}"
                        data-program-title="{{ $program['title'] }}"
                        @checked($whoAreYouSelected === $program['id'])
                        class="js-audience-who-radio"
                    >
                    <span>{{ $program['title'] }}</span>
                </label>
            @endforeach
        </div>
    </div>

    @once
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input.js-audience-who-radio').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (!radio.checked) return;
                    var form = radio.closest('form');
                    if (!form) return;

                    var contactProgram = radio.getAttribute('data-contact-program') || '';
                    var contactCategory = radio.getAttribute('data-contact-category') || '';
                    var interestedField = form.querySelector('.js-audience-interested-program');
                    var categoryField = form.querySelector('.js-audience-category');

                    if (interestedField && contactProgram) interestedField.value = contactProgram;
                    if (categoryField && contactCategory) categoryField.value = contactCategory;
                });
            });
        });
        </script>
        @endpush
    @endonce
@endif
