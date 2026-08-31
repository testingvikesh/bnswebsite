(function () {
    'use strict';

    var dismissed = Object.create(null);
    var closing = Object.create(null);

    function hidePreloader() {
        var el = document.querySelector('.js-preloader');
        if (!el) return;
        el.style.cssText = 'display:none!important;visibility:hidden!important;opacity:0!important;pointer-events:none!important;z-index:-1!important;';
    }

    function removeBackdrops() {
        document.querySelectorAll(
            '.modal-backdrop, [data-bns-intro-session-backdrop], [data-bns-quick-register-backdrop]'
        ).forEach(function (node) {
            if (node.parentNode) node.parentNode.removeChild(node);
        });
    }

    function unlockBody() {
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
        document.documentElement.style.removeProperty('overflow');
    }

    function cleanUrlOpenParam() {
        try {
            var url = new URL(window.location.href);
            var changed = false;
            if (url.searchParams.has('open')) {
                url.searchParams.delete('open');
                changed = true;
            }
            var hash = (url.hash || '').replace(/^#/, '');
            var openHashes = {
                'introduction-session-admission': 1,
                'introduction-session': 1,
                'book-your-spot': 1,
                'book-your-spot-now': 1
            };
            if (openHashes[hash]) {
                url.hash = '';
                changed = true;
            }
            if (changed) {
                window.history.replaceState(null, '', url.pathname + url.search + (url.hash || ''));
            }
        } catch (e) {}
    }

    function clearBodyOpenFlags() {
        document.body.setAttribute('data-bns-open-intro-session', '0');
        document.body.setAttribute('data-bns-open-quick-register', '0');
    }

    function forceClose(modalEl) {
        if (!modalEl) return false;

        var id = modalEl.id || 'unknown';
        closing[id] = true;
        dismissed[id] = true;
        clearBodyOpenFlags();
        cleanUrlOpenParam();
        hidePreloader();

        try {
            if (window.bootstrap && window.bootstrap.Modal) {
                var inst = window.bootstrap.Modal.getInstance(modalEl);
                if (inst) {
                    try {
                        inst._isShown = false;
                        inst._isTransitioning = false;
                    } catch (e0) {}
                    try { inst.hide(); } catch (e1) {}
                    try { inst.dispose(); } catch (e2) {}
                }
            }
        } catch (e) {}

        modalEl.classList.remove('show');
        modalEl.classList.add('bns-modal-is-closed');
        modalEl.style.setProperty('display', 'none', 'important');
        modalEl.setAttribute('aria-hidden', 'true');
        modalEl.removeAttribute('aria-modal');
        modalEl.removeAttribute('role');

        removeBackdrops();
        unlockBody();

        window.setTimeout(function () {
            removeBackdrops();
            unlockBody();
            modalEl.classList.remove('show');
            modalEl.classList.add('bns-modal-is-closed');
            modalEl.style.setProperty('display', 'none', 'important');
            closing[id] = false;
        }, 80);

        return false;
    }

    function forceOpen(modalEl) {
        if (!modalEl) return;
        var id = modalEl.id || '';
        if (dismissed[id] || closing[id]) return;

        hidePreloader();
        modalEl.classList.remove('bns-modal-is-closed');
        if (!modalEl.classList.contains('fade')) {
            modalEl.classList.add('fade');
        }
        modalEl.classList.add('show');
        modalEl.style.removeProperty('display');
        modalEl.style.display = 'block';
        modalEl.setAttribute('aria-modal', 'true');
        modalEl.setAttribute('aria-hidden', 'false');
        modalEl.setAttribute('role', 'dialog');
        document.body.classList.add('modal-open');
        document.body.style.overflow = 'hidden';

        if (!document.querySelector('.modal-backdrop')) {
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    }

    function shouldOpenIntro() {
        if (dismissed.bnsIntroSessionModal || closing.bnsIntroSessionModal) return false;
        if (document.body.getAttribute('data-bns-open-intro-session') === '1') return true;
        try {
            var open = new URLSearchParams(window.location.search).get('open');
            if (open === 'introduction-session' || open === 'book-your-spot') return true;
        } catch (e) {}
        var hash = (window.location.hash || '').replace(/^#/, '');
        return ['introduction-session-admission', 'introduction-session', 'book-your-spot', 'book-your-spot-now'].indexOf(hash) !== -1;
    }

    function closeFromEvent(event) {
        var target = event.target;
        if (!target || typeof target.closest !== 'function') return;

        var btn = target.closest(
            '.bns-modal-close, [data-bns-close-modal], .modal [data-bs-dismiss="modal"], .modal .btn-close'
        );
        if (!btn) return;

        var modalEl = btn.closest('.modal');
        if (!modalEl) {
            var id = btn.getAttribute('data-bns-close-modal') || '';
            if (id) modalEl = document.getElementById(id);
        }
        if (!modalEl) return;

        event.preventDefault();
        event.stopPropagation();
        if (typeof event.stopImmediatePropagation === 'function') {
            event.stopImmediatePropagation();
        }
        forceClose(modalEl);
    }

    document.addEventListener('click', closeFromEvent, true);

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape' && event.keyCode !== 27) return;
        var openModal = document.querySelector('.modal.show:not(.bns-modal-is-closed)');
        if (openModal) {
            event.preventDefault();
            forceClose(openModal);
        }
    }, true);

    document.addEventListener('click', function (event) {
        var trigger = event.target && event.target.closest
            ? event.target.closest('[data-bs-toggle="modal"][data-bs-target]')
            : null;
        if (!trigger) return;
        var id = (trigger.getAttribute('data-bs-target') || '').replace(/^#/, '');
        if (!id) return;
        dismissed[id] = false;
        closing[id] = false;
        var modalEl = document.getElementById(id);
        if (modalEl) {
            modalEl.classList.remove('bns-modal-is-closed');
            modalEl.style.removeProperty('display');
        }
    }, true);

    document.addEventListener('show.bs.modal', function (event) {
        if (!event.target || !event.target.id) return;
        if (closing[event.target.id] || dismissed[event.target.id]) {
            event.preventDefault();
            forceClose(event.target);
            return;
        }
        event.target.classList.remove('bns-modal-is-closed');
        hidePreloader();
    });

    function boot() {
        hidePreloader();
        var intro = document.getElementById('bnsIntroSessionModal');
        if (intro && intro.classList.contains('show') && !dismissed.bnsIntroSessionModal) {
            forceOpen(intro);
            cleanUrlOpenParam();
            return;
        }
        if (shouldOpenIntro()) {
            forceOpen(document.getElementById('bnsIntroSessionModal'));
            cleanUrlOpenParam();
        }
        if (!dismissed.bnsQuickRegisterModal && document.body.getAttribute('data-bns-open-quick-register') === '1') {
            forceOpen(document.getElementById('bnsQuickRegisterModal'));
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    window.addEventListener('load', function () {
        hidePreloader();
        if (shouldOpenIntro()) {
            forceOpen(document.getElementById('bnsIntroSessionModal'));
            cleanUrlOpenParam();
        }
    });

    window.bnsCloseModal = forceClose;
    window.bnsCloseIntroModal = function () {
        return forceClose(document.getElementById('bnsIntroSessionModal'));
    };
})();
