(function () {
    'use strict';

    var nativeSubmit = HTMLFormElement.prototype.submit;
    var pending = false;

    function resolveSiteKey(form) {
        var fromWindow = window.BNS_RECAPTCHA_SITE_KEY || '';
        if (fromWindow) {
            return fromWindow;
        }
        if (!form) {
            return '';
        }
        var marked = form.querySelector('[data-recaptcha-site-key]');
        if (marked) {
            return marked.getAttribute('data-recaptcha-site-key') || '';
        }
        return form.getAttribute('data-recaptcha-site-key') || '';
    }

    function ensureApi(siteKey) {
        if (!siteKey) {
            return Promise.reject(new Error('Missing reCAPTCHA site key'));
        }

        if (window.grecaptcha && typeof window.grecaptcha.execute === 'function') {
            return Promise.resolve();
        }

        if (!document.querySelector('script[data-bns-recaptcha-api="1"]')) {
            var script = document.createElement('script');
            script.src = 'https://www.google.com/recaptcha/api.js?render=' + encodeURIComponent(siteKey);
            script.async = true;
            script.defer = true;
            script.setAttribute('data-bns-recaptcha-api', '1');
            document.head.appendChild(script);
        }

        return waitForGrecaptcha();
    }

    function waitForGrecaptcha(timeoutMs) {
        timeoutMs = timeoutMs || 12000;

        return new Promise(function (resolve, reject) {
            var started = Date.now();

            function check() {
                if (window.grecaptcha && typeof window.grecaptcha.execute === 'function') {
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

    function execute(form, action) {
        var siteKey = resolveSiteKey(form);
        if (!siteKey) {
            return Promise.reject(new Error('Missing reCAPTCHA site key'));
        }

        return ensureApi(siteKey).then(function () {
            return new Promise(function (resolve, reject) {
                var run = function () {
                    window.grecaptcha.execute(siteKey, { action: action || 'contact' })
                        .then(function (token) {
                            if (!token) {
                                reject(new Error('Empty reCAPTCHA token'));
                                return;
                            }
                            resolve(token);
                        })
                        .catch(reject);
                };

                if (typeof window.grecaptcha.ready === 'function') {
                    window.grecaptcha.ready(run);
                } else {
                    run();
                }
            });
        });
    }

    function setToken(form, token) {
        if (!form || !token) {
            return;
        }

        var names = ['bns_security', 'recaptcha_token'];
        var i;
        for (i = 0; i < names.length; i += 1) {
            var input = form.querySelector('input[name="' + names[i] + '"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = names[i];
                form.appendChild(input);
            }
            input.value = token;
        }

        // Do not write g-recaptcha-response: Google also injects an empty
        // textarea with that name, and PHP would keep the empty duplicate.
    }

    function existingToken(form) {
        if (!form) {
            return '';
        }
        var names = ['bns_security', 'recaptcha_token'];
        var i;
        for (i = 0; i < names.length; i += 1) {
            var input = form.querySelector('input[name="' + names[i] + '"]');
            if (input && String(input.value || '').trim()) {
                return String(input.value).trim();
            }
        }
        return '';
    }

    window.bnsRecaptchaAttach = function (form, action) {
        var el = form && form.jquery ? form[0] : form;
        var act = action || (el && el.getAttribute ? el.getAttribute('data-recaptcha-action') : '') || 'contact';

        return execute(el, act).then(function (token) {
            setToken(el, token);
            return token;
        });
    };

    HTMLFormElement.prototype.submit = function () {
        var form = this;
        if (!form || !form.classList || !form.classList.contains('js-recaptcha-v3')) {
            return nativeSubmit.call(form);
        }
        if (existingToken(form)) {
            return nativeSubmit.call(form);
        }
        if (pending) {
            return;
        }

        pending = true;
        var action = form.getAttribute('data-recaptcha-action') || 'contact';
        execute(form, action).then(function (token) {
            setToken(form, token);
            pending = false;
            nativeSubmit.call(form);
        }).catch(function () {
            pending = false;
            window.alert('Security check could not be completed. Please reload the page and try again.');
        });
    };

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form || !form.classList || !form.classList.contains('js-recaptcha-v3')) {
            return;
        }
        // Intro/register forms with mobile checks attach the token in jQuery
        // submitHandler, then call native submit(). Do not hijack that path.
        if (form.hasAttribute('data-check-mobile-url')) {
            return;
        }
        if (existingToken(form)) {
            return;
        }
        if (!resolveSiteKey(form)) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        var action = form.getAttribute('data-recaptcha-action') || 'contact';
        execute(form, action).then(function (token) {
            setToken(form, token);
            nativeSubmit.call(form);
        }).catch(function () {
            window.alert('Security check could not be completed. Please reload the page and try again.');
        });
    }, true);
})();
