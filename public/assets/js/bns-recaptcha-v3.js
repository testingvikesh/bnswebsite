(function () {
    'use strict';

    var siteKey = window.BNS_RECAPTCHA_SITE_KEY || '';

    function waitForGrecaptcha(timeoutMs) {
        timeoutMs = timeoutMs || 10000;

        return new Promise(function (resolve, reject) {
            var started = Date.now();

            function check() {
                if (window.grecaptcha && typeof window.grecaptcha.execute === 'function' && typeof window.grecaptcha.ready === 'function') {
                    resolve();
                    return;
                }
                if (Date.now() - started >= timeoutMs) {
                    reject(new Error('reCAPTCHA did not load'));
                    return;
                }
                window.setTimeout(check, 80);
            }

            check();
        });
    }

    function execute(action) {
        if (!siteKey) {
            return Promise.resolve('');
        }

        return waitForGrecaptcha().then(function () {
            return new Promise(function (resolve, reject) {
                window.grecaptcha.ready(function () {
                    window.grecaptcha.execute(siteKey, { action: action || 'contact' })
                        .then(function (token) {
                            if (!token) {
                                reject(new Error('Empty reCAPTCHA token'));
                                return;
                            }
                            resolve(token);
                        })
                        .catch(reject);
                });
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

        var alt = form.querySelector('input[name="recaptcha_token"]');
        if (!alt) {
            alt = document.createElement('input');
            alt.type = 'hidden';
            alt.name = 'recaptcha_token';
            form.appendChild(alt);
        }
        alt.value = token || '';
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
            form.removeAttribute('data-recaptcha-ready');
            window.alert('Security check could not be completed. Please reload the page and try again.');
        });
    }, true);
})();
