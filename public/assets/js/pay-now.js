(function () {
    'use strict';

    var root = document.querySelector('[data-pay-now]');
    if (!root) {
        return;
    }

    var lookupModal = document.getElementById('bnsPaymentLookupModal');
    var submitForm = document.getElementById('bnsPayNowSubmitForm');
    var submitBtn = document.getElementById('bnsPayNowSubmitBtn');

    function setLanguage(lang) {
        root.querySelectorAll('[data-pay-now-lang]').forEach(function (tab) {
            tab.classList.toggle('is-active', tab.getAttribute('data-pay-now-lang') === lang);
        });

        root.querySelectorAll('[data-pay-now-lang-panel]').forEach(function (panel) {
            var active = panel.getAttribute('data-pay-now-lang-panel') === lang;
            panel.hidden = !active;
            panel.classList.toggle('is-active', active);
        });
    }

    root.querySelectorAll('[data-pay-now-lang]').forEach(function (tab) {
        tab.addEventListener('click', function () {
            setLanguage(tab.getAttribute('data-pay-now-lang'));
        });
    });

    if (lookupModal) {
        if (lookupModal.parentElement !== document.body) {
            document.body.appendChild(lookupModal);
        }

        if (lookupModal.getAttribute('data-open-on-load') === '1' && window.bootstrap) {
            document.body.classList.add('modal-open', 'bns-membership-modal-open');
            window.bootstrap.Modal.getOrCreateInstance(lookupModal).show();
        }

        lookupModal.addEventListener('show.bs.modal', function () {
            document.body.classList.add('bns-membership-modal-open');
        });

        lookupModal.addEventListener('shown.bs.modal', function () {
            var backdrops = document.querySelectorAll('.modal-backdrop');
            if (backdrops.length) {
                backdrops[backdrops.length - 1].classList.add('bns-membership-upload-backdrop');
            }

            var nameInput = document.getElementById('pay_now_full_name');
            if (nameInput) {
                nameInput.focus();
            }
        });

        lookupModal.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('bns-membership-modal-open');
        });
    }

    if (submitForm && submitBtn) {
        submitForm.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        });
    }
})();
