(function () {
    var meta = document.querySelector('meta[name="csrf-token"]');
    var csrfUrlMeta = document.querySelector('meta[name="csrf-token-url"]');
    var csrfUrl = (csrfUrlMeta && csrfUrlMeta.getAttribute('content'))
        || (document.body && document.body.getAttribute('data-csrf-url'))
        || '/csrf-token';

    function applyToken(token) {
        if (!token) {
            return;
        }

        if (meta) {
            meta.setAttribute('content', token);
        }

        document.querySelectorAll('input[name="_token"]').forEach(function (input) {
            input.value = token;
        });

        document.querySelectorAll('[data-csrf]').forEach(function (el) {
            el.setAttribute('data-csrf', token);
        });
    }

    function currentToken() {
        return (meta && meta.getAttribute('content')) || '';
    }

    function refreshToken() {
        return fetch(csrfUrl, {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('csrf-refresh-failed');
            }
            return response.json();
        }).then(function (data) {
            var token = data && data.token ? data.token : '';
            applyToken(token);
            return token;
        }).catch(function () {
            return '';
        });
    }

    function bindJqueryCsrf() {
        if (!window.jQuery || window.jQuery.bnsCsrfBound) {
            return;
        }

        window.jQuery.bnsCsrfBound = true;
        window.jQuery(document).ajaxSend(function (event, xhr) {
            var token = currentToken();
            if (token) {
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
                xhr.setRequestHeader('X-XSRF-TOKEN', token);
            }
        });
    }

    window.bnsRefreshCsrfToken = refreshToken;
    window.bnsApplyCsrfToken = applyToken;

    setInterval(refreshToken, 8 * 60 * 1000);

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            refreshToken();
        }
    });

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            refreshToken();
        }
    });

    document.addEventListener('show.bs.modal', function () {
        refreshToken();
    });

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }
        if (form.dataset.bnsCsrfReady === '1') {
            return;
        }
        if (form.classList.contains('js-logout-form') || form.getAttribute('data-bns-skip-csrf') === '1') {
            return;
        }
        if ((form.getAttribute('action') || '').indexOf('/logout') !== -1) {
            return;
        }
        if (form.dataset.bnsCsrfBusy === '1') {
            event.preventDefault();
            return;
        }
        if ((form.getAttribute('method') || 'get').toLowerCase() !== 'post') {
            return;
        }
        if (!form.querySelector('input[name="_token"]')) {
            return;
        }
        if (window.jQuery && window.jQuery(form).data('validator')) {
            return;
        }

        event.preventDefault();
        form.dataset.bnsCsrfBusy = '1';
        refreshToken().finally(function () {
            form.dataset.bnsCsrfReady = '1';
            form.dataset.bnsCsrfBusy = '0';
            if (typeof form.requestSubmit === 'function') {
                var submitter = event.submitter || null;
                if (submitter) {
                    form.requestSubmit(submitter);
                } else {
                    form.requestSubmit();
                }
            } else {
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    }, true);

    bindJqueryCsrf();
    document.addEventListener('DOMContentLoaded', bindJqueryCsrf);
    window.addEventListener('load', bindJqueryCsrf);

    if (window.fetch) {
        var originalFetch = window.fetch;
        window.fetch = function () {
            return originalFetch.apply(this, arguments).then(function (response) {
                if (response.status === 419) {
                    response.clone().json().then(function (payload) {
                        if (payload && payload.token) {
                            applyToken(payload.token);
                        } else {
                            refreshToken();
                        }
                    }).catch(function () {
                        refreshToken();
                    });
                }
                return response;
            });
        };
    }
})();
