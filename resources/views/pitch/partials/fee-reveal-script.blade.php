@once('bns-fee-reveal-script')
    <script>
    (function () {
        'use strict';

        document.addEventListener('click', function (event) {
            var toggle = event.target.closest('[data-bns-fee-reveal-toggle]');
            if (!toggle) return;

            var root = toggle.closest('[data-bns-fee-reveal]');
            if (!root) return;

            var amount = root.querySelector('[data-bns-fee-reveal-amount]');
            var icon = toggle.querySelector('i');
            var hint = toggle.querySelector('.bns-fee-reveal__hint');
            if (!amount) return;

            var isOpen = root.classList.toggle('is-open');
            amount.hidden = !isOpen;
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            toggle.setAttribute('aria-label', isOpen ? 'Hide fees' : 'Click to view fees');

            if (icon) {
                icon.classList.toggle('fa-eye', !isOpen);
                icon.classList.toggle('fa-eye-slash', isOpen);
            }
            if (hint) {
                hint.hidden = isOpen;
            }
        });
    })();
    </script>
@endonce
