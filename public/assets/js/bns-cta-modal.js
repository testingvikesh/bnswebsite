(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById('bnsQuickRegisterModal');
        if (!modalEl) return;

        var form = modalEl.querySelector('#bnsQuickRegisterForm');
        if (!form) return;

        var programIdInput = form.querySelector('.bns-quick-register-form__program-id');
        var interestedProgramInput = form.querySelector('.bns-quick-register-form__interested-program');
        var categoryInput = form.querySelector('.bns-quick-register-form__category');
        var messageInput = form.querySelector('.bns-quick-register-form__message');
        var eyebrowEl = document.getElementById('bnsQuickRegisterEyebrow');
        var introEl = document.getElementById('bnsQuickRegisterIntro');
        var radios = form.querySelectorAll('input[name="register_program_choice"]');

        function applyProgramData(source) {
            if (!source) return;

            var contactProgram = source.getAttribute('data-contact-program');
            var contactCategory = source.getAttribute('data-contact-category');
            var programTitle = source.getAttribute('data-program-title');

            if (contactProgram && interestedProgramInput) interestedProgramInput.value = contactProgram;
            if (contactCategory && categoryInput) categoryInput.value = contactCategory;
            if (programTitle && messageInput) {
                messageInput.value = 'Registration request for ' + programTitle + ' at Business Navachar School (BNS).';
            }
        }

        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (!radio.checked) return;
                if (programIdInput) programIdInput.value = radio.value;
                applyProgramData(radio);
            });
        });

        modalEl.addEventListener('show.bs.modal', function (event) {
            var trigger = event.relatedTarget;
            if (!trigger) return;

            var registerProgramId = trigger.getAttribute('data-register-program-id') || '';
            var contactProgram = trigger.getAttribute('data-contact-program') || '';
            var contactCategory = trigger.getAttribute('data-contact-category') || '';
            var programTitle = trigger.getAttribute('data-program-title') || '';

            if (programIdInput) programIdInput.value = registerProgramId;
            if (contactProgram && interestedProgramInput) interestedProgramInput.value = contactProgram;
            if (contactCategory && categoryInput) categoryInput.value = contactCategory;
            if (programTitle) {
                if (messageInput) messageInput.value = 'Registration request for ' + programTitle + ' at Business Navachar School (BNS).';
                if (eyebrowEl) eyebrowEl.textContent = programTitle;
                if (introEl) introEl.textContent = 'Share your basic details for ' + programTitle + ' — our Admission Team will contact you shortly.';
            }

            var matched = false;
            radios.forEach(function (radio) {
                var isMatch = registerProgramId !== '' && radio.value === registerProgramId;
                radio.checked = isMatch;
                if (isMatch) matched = true;
            });

            if (!matched && registerProgramId === '' && !contactProgram) {
                if (eyebrowEl) eyebrowEl.textContent = 'Business Navachar School';
                if (introEl) introEl.textContent = 'Share your basic details — our Admission Team will contact you shortly. No need to leave this page.';
            }
        });
    });
})();
