(function () {
    'use strict';

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

    function applyTokenChunks(data, token) {
        if (!token) {
            return;
        }
        var chunk = 60;
        var index = 1;
        var i;
        data.set('form_check', '1');
        for (i = 0; i < token.length; i += chunk) {
            data.set('fc' + index, token.substr(i, chunk));
            index += 1;
        }
        data.set('fc_count', String(index - 1));
    }

    function csrfToken(form) {
        var input = form && form.querySelector ? form.querySelector('input[name="_token"]') : null;
        return (input && input.value)
            || (document.querySelector('meta[name="csrf-token"]') || {}).content
            || '';
    }

    function showFormErrors(form, messages) {
        var box = form.closest('.modal-body')
            ? form.closest('.modal-body').querySelector('.js-bns-form-alert')
            : form.querySelector('.js-bns-form-alert');
        var text = (messages || []).filter(Boolean).join(' ');
        if (!text) {
            text = 'Please check the form and try again.';
        }
        if (box) {
            box.hidden = false;
            box.textContent = text;
            box.scrollIntoView({ block: 'nearest' });
            return;
        }
        window.alert(text);
    }

    function parseJsonSafe(response) {
        return response.text().then(function (raw) {
            if (!raw) {
                return {};
            }
            try {
                return JSON.parse(raw);
            } catch (e) {
                return {};
            }
        });
    }

    function flattenErrors(payload) {
        var messages = [];
        var errors = payload && payload.errors ? payload.errors : null;
        var key;
        if (payload && payload.message) {
            messages.push(payload.message);
        }
        if (errors) {
            for (key in errors) {
                if (Object.prototype.hasOwnProperty.call(errors, key) && errors[key] && errors[key].length) {
                    messages.push(errors[key][0]);
                }
            }
        }
        return messages;
    }

    window.bnsRecaptchaAttach = function (form, action) {
        var el = form && form.jquery ? form[0] : form;
        var act = action || (el && el.getAttribute ? el.getAttribute('data-recaptcha-action') : '') || 'contact';
        return execute(el, act);
    };

    window.bnsSubmitProtectedForm = function (form, token) {
        var el = form && form.jquery ? form[0] : form;
        var data = new FormData(el);
        applyTokenChunks(data, token);
        data.delete('g-recaptcha-response');
        data.delete('recaptcha_token');
        data.delete('bns_security');

        return fetch(el.getAttribute('action'), {
            method: 'POST',
            body: data,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken(el)
            }
        }).then(function (response) {
            if (response.status === 419) {
                throw new Error('Page expired. Please reload and try again.');
            }

            return parseJsonSafe(response).then(function (payload) {
                if (response.ok && payload && payload.redirect) {
                    window.location.href = payload.redirect;
                    return payload;
                }
                if (response.ok && payload && payload.ok) {
                    if (payload.message) {
                        window.alert(payload.message);
                    }
                    window.location.reload();
                    return payload;
                }
                throw new Error(flattenErrors(payload).join(' ') || 'Please try submitting the form again.');
            });
        });
    };

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form || !form.classList || !form.classList.contains('js-recaptcha-v3')) {
            return;
        }
        if (form.hasAttribute('data-check-mobile-url')) {
            return;
        }
        if (!resolveSiteKey(form)) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        var action = form.getAttribute('data-recaptcha-action') || 'contact';
        execute(form, action).then(function (token) {
            return window.bnsSubmitProtectedForm(form, token);
        }).catch(function (error) {
            showFormErrors(form, [error && error.message ? error.message : 'Security check could not be completed.']);
        });
    }, true);
})();
