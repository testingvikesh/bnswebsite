(function () {
    function topOffset() {
        var bar = document.querySelector('.bns-reporting-topbar');
        return (bar ? bar.offsetHeight : 0) + 12;
    }

    function scrollToEl(el) {
        if (!el) {
            return;
        }

        var y = el.getBoundingClientRect().top + window.pageYOffset - topOffset();
        window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });
    }

    function setQueryParam(url, key, value) {
        var next = new URL(url, window.location.origin);
        if (value === null || value === '') {
            next.searchParams.delete(key);
        } else {
            next.searchParams.set(key, String(value));
        }
        return next.pathname + next.search + next.hash;
    }

    function selectSession(sessionNo, options) {
        options = options || {};
        var scroll = options.scroll !== false;
        var push = options.push !== false;
        var session = String(sessionNo);

        document.querySelectorAll('.bns-reporting-session-tab').forEach(function (tab) {
            var active = tab.getAttribute('data-session') === session;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        document.querySelectorAll('[data-session-panel]').forEach(function (panel) {
            var match = panel.getAttribute('data-session-panel') === session;
            panel.hidden = !match;
        });

        document.querySelectorAll('.js-reporting-session-field').forEach(function (field) {
            field.value = session;
        });

        document.querySelectorAll('.js-reporting-export, .js-reporting-today-link, .js-reporting-all-link').forEach(function (link) {
            if (link.getAttribute('href')) {
                link.setAttribute('href', setQueryParam(link.getAttribute('href'), 'session', session));
            }
        });

        if (push) {
            var nextUrl = setQueryParam(window.location.href, 'session', session);
            if (nextUrl !== window.location.pathname + window.location.search + window.location.hash) {
                history.pushState({ session: session }, '', nextUrl);
            }
        }

        if (scroll) {
            var list = document.getElementById('reporting-records-' + session)
                || document.querySelector('[data-session-panel="' + session + '"] .bns-reporting-table-card');
            scrollToEl(list);
        }
    }

    function scrollToHash() {
        var hash = window.location.hash.replace('#', '');
        if (!hash) {
            return;
        }
        scrollToEl(document.getElementById(hash));
    }

    document.querySelectorAll('a.bns-reporting-stat[href]').forEach(function (box) {
        box.addEventListener('click', function (event) {
            if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) {
                return;
            }

            var target = new URL(box.href, window.location.origin);
            var here = new URL(window.location.href);
            var samePage = target.pathname === here.pathname && target.search === here.search;

            if (!samePage) {
                return;
            }

            event.preventDefault();
            var hash = target.hash.replace('#', '');
            if (hash) {
                history.replaceState(null, '', target.hash);
                scrollToEl(document.getElementById(hash));
            }
        });
    });

    document.querySelectorAll('.bns-reporting-session-tab[data-session]').forEach(function (tab) {
        tab.addEventListener('click', function (event) {
            if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) {
                return;
            }

            event.preventDefault();
            selectSession(tab.getAttribute('data-session'), { scroll: true, push: true });
        });
    });

    window.addEventListener('popstate', function () {
        var session = new URL(window.location.href).searchParams.get('session');
        if (session && document.querySelector('[data-session-panel="' + session + '"]')) {
            selectSession(session, { scroll: false, push: false });
        }
    });

    document.querySelectorAll('.js-reporting-filter-form, .bns-reporting-filter form').forEach(function (form) {
        form.querySelectorAll('select, input[type="date"]').forEach(function (field) {
            field.addEventListener('change', function () {
                form.requestSubmit ? form.requestSubmit() : form.submit();
            });
        });
    });

    var backBtn = document.querySelector('.bns-reporting-back-top');
    if (backBtn) {
        var toggleBack = function () {
            backBtn.classList.toggle('is-visible', window.pageYOffset > 280);
        };

        window.addEventListener('scroll', toggleBack, { passive: true });
        toggleBack();

        backBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            window.setTimeout(scrollToHash, 50);
        });
    } else {
        window.setTimeout(scrollToHash, 50);
    }
    window.addEventListener('load', scrollToHash);
})();
