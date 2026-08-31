(function () {
    function boot() {
        var catalogNode = document.getElementById('bnsMessageCatalog');
        var modalEl = document.getElementById('bnsMessageViewerModal');
        if (!catalogNode || !modalEl) {
            return;
        }
        if (!window.bootstrap || !bootstrap.Modal) {
            return;
        }

        // Keep modal at document.body so page-wrapper overflow cannot clip it
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }

        var catalog = [];
        try {
            catalog = JSON.parse(catalogNode.textContent || '[]');
        } catch (e) {
            catalog = [];
        }

        var state = { section: null, index: 0 };
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl, {
            backdrop: true,
            keyboard: true,
            focus: true
        });

        var ui = {
            section: modalEl.querySelector('[data-message-section]'),
            title: modalEl.querySelector('[data-message-title]'),
            counter: modalEl.querySelector('[data-message-counter]'),
            body: modalEl.querySelector('[data-message-body]'),
            links: modalEl.querySelector('[data-message-links]'),
            imageWrap: modalEl.querySelector('[data-message-image-wrap]'),
            image: modalEl.querySelector('[data-message-image]'),
            prev: modalEl.querySelector('[data-message-nav="prev"]'),
            next: modalEl.querySelector('[data-message-nav="next"]'),
            copy: modalEl.querySelector('[data-message-copy]'),
            send: modalEl.querySelector('[data-message-send]'),
            cta: modalEl.querySelector('[data-message-cta]'),
            ctaLabel: modalEl.querySelector('[data-message-cta-label]')
        };

        function sectionItems(section) {
            return catalog.filter(function (item) {
                return item.section === section;
            });
        }

        function currentItem() {
            var items = sectionItems(state.section);
            return items[state.index] || null;
        }

        function escapeAttr(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function renderMessage() {
            var item = currentItem();
            if (!item) {
                return false;
            }

            var items = sectionItems(state.section);
            if (ui.section) {
                ui.section.textContent = item.section_title || 'Message';
            }
            if (ui.title) {
                ui.title.textContent = item.title || 'Message';
            }
            if (ui.counter) {
                ui.counter.textContent = (state.index + 1) + ' / ' + items.length;
            }

            if (ui.prev) {
                ui.prev.disabled = state.index <= 0;
            }
            if (ui.next) {
                ui.next.disabled = state.index >= items.length - 1;
            }

            if (ui.body) {
                ui.body.innerHTML = (item.body || []).map(function (html) {
                    return '<p>' + html + '</p>';
                }).join('');
            }

            if (ui.imageWrap && ui.image) {
                if (item.image) {
                    ui.image.src = item.image;
                    ui.image.alt = item.title || 'Message';
                    ui.imageWrap.classList.remove('d-none');
                } else {
                    ui.image.removeAttribute('src');
                    ui.imageWrap.classList.add('d-none');
                }
            }

            if (ui.links) {
                var links = item.links || [];
                if (links.length) {
                    ui.links.innerHTML = links.map(function (link) {
                        var external = link.external
                            ? ' target="_blank" rel="noopener noreferrer"'
                            : '';
                        return '<a href="' + escapeAttr(link.url) + '" class="bns-message-modal__link"' + external + '>' +
                            '<i class="fas fa-link" aria-hidden="true"></i> ' + escapeAttr(link.label) +
                            '</a>';
                    }).join('');
                    ui.links.classList.remove('d-none');
                } else {
                    ui.links.innerHTML = '';
                    ui.links.classList.add('d-none');
                }
            }

            if (ui.send) {
                ui.send.href = 'https://wa.me/?text=' + encodeURIComponent(item.plain || '');
            }

            if (ui.cta && ui.ctaLabel) {
                if (item.cta && item.cta.url) {
                    ui.cta.href = item.cta.url;
                    ui.ctaLabel.textContent = item.cta.label || 'Continue';
                    ui.cta.classList.remove('d-none');
                } else {
                    ui.cta.classList.add('d-none');
                    ui.cta.removeAttribute('href');
                }
            }

            var scrollBody = modalEl.querySelector('.modal-body');
            if (scrollBody) {
                scrollBody.scrollTop = 0;
            }

            return true;
        }

        function openMessage(section, index) {
            state.section = section;
            state.index = Math.max(0, parseInt(index, 10) || 0);
            renderMessage();
            modal.show();
        }

        function move(delta) {
            var items = sectionItems(state.section);
            var nextIndex = state.index + delta;
            if (nextIndex < 0 || nextIndex >= items.length) {
                return;
            }
            state.index = nextIndex;
            renderMessage();
        }

        document.addEventListener('click', function (event) {
            var openBtn = event.target.closest('[data-message-open]');
            if (openBtn) {
                event.preventDefault();
                openMessage(
                    openBtn.getAttribute('data-section'),
                    openBtn.getAttribute('data-index')
                );
                return;
            }

            var navBtn = event.target.closest('[data-message-nav]');
            if (navBtn && modalEl.contains(navBtn)) {
                event.preventDefault();
                event.stopPropagation();
                move(navBtn.getAttribute('data-message-nav') === 'prev' ? -1 : 1);
                return;
            }

            var copyBtn = event.target.closest('[data-message-copy]');
            if (copyBtn && modalEl.contains(copyBtn)) {
                event.preventDefault();
                var item = currentItem();
                var text = item && item.plain ? item.plain : '';
                if (!text) {
                    return;
                }

                var done = function () {
                    var original = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i> Copied';
                    setTimeout(function () {
                        copyBtn.innerHTML = original;
                    }, 1600);
                };

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(done).catch(function () {});
                    return;
                }

                var area = document.createElement('textarea');
                area.value = text;
                document.body.appendChild(area);
                area.select();
                try { document.execCommand('copy'); done(); } catch (e) {}
                document.body.removeChild(area);
            }
        });

        if (ui.send) {
            ui.send.addEventListener('click', function () {
                var item = currentItem();
                if (!item) {
                    return;
                }
                ui.send.href = 'https://wa.me/?text=' + encodeURIComponent(item.plain || '');
            });
        }

        document.addEventListener('keydown', function (event) {
            if (!modalEl.classList.contains('show')) {
                return;
            }
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                move(-1);
            }
            if (event.key === 'ArrowRight') {
                event.preventDefault();
                move(1);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();

