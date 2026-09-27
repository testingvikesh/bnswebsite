(function () {
    'use strict';

    var siteKey = window.BNS_RECAPTCHA_SITE_KEY || '';

    function execute(action) {
        if (!siteKey || !window.grecaptcha || typeof window.grecaptcha.execute !== 'function') {
            return Promise.resolve('');
        }

        return new Promise(function (resolve, reject) {
            window.grecaptcha.ready(function () {
                window.grecaptcha.execute(siteKey, { action: action || 'contact' })
                    .then(resolve)
                    .catch(reject);
            });
        });
    }

    function setToken(form, token) {
        if (!form) {
            return;
        }

        var input = form.querySelector('input[name="g-recaptcha-response"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'g-recaptcha-response';
            form.appendChild(input);
        }
        input.value = token || '';
    }

    window.bnsRecaptchaAttach = function (form, action) {
        return execute(action).then(function (token) {
            setToken(form, token);
            return token;
        });
    };

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form || !form.classList || !form.classList.contains('js-recaptcha-v3')) {
            return;
        }
        if (form.classList.contains('bns-intro-session-form')) {
            return;
        }
        if (form.getAttribute('data-recaptcha-ready') === '1') {
            return;
        }
        if (!siteKey) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        var action = form.getAttribute('data-recaptcha-action') || 'contact';
        execute(action).then(function (token) {
            setToken(form, token);
            form.setAttribute('data-recaptcha-ready', '1');
            HTMLFormElement.prototype.submit.call(form);
        }).catch(function () {
            window.alert('Security check could not be completed. Please reload the page and try again.');
        });
    }, true);
})();
