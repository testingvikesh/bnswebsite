<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('crmRemarkModal');
    if (!modalEl) {
        return;
    }
    var form = document.getElementById('crmRemarkForm');
    var saveBtn = document.getElementById('crmRemarkSave');
    var readonly = document.getElementById('crmRemarkReadonly');
    var statusSelect = document.getElementById('crmRemarkStatus');
    var noteField = document.getElementById('crmRemarkNote');

    function prepareModal() {
        if (typeof window.bnsAllowModal === 'function') {
            window.bnsAllowModal(modalEl);
        } else {
            modalEl.classList.remove('bns-modal-is-closed');
            modalEl.style.removeProperty('display');
            modalEl.style.removeProperty('visibility');
            modalEl.style.removeProperty('opacity');
            modalEl.style.removeProperty('pointer-events');
        }
    }

    function getModalInstance() {
        if (!window.bootstrap || !bootstrap.Modal) {
            return null;
        }
        if (typeof bootstrap.Modal.getOrCreateInstance === 'function') {
            return bootstrap.Modal.getOrCreateInstance(modalEl);
        }
        var existing = typeof bootstrap.Modal.getInstance === 'function'
            ? bootstrap.Modal.getInstance(modalEl)
            : null;
        return existing || new bootstrap.Modal(modalEl);
    }

    function showModalFallback() {
        modalEl.classList.add('show');
        modalEl.style.setProperty('display', 'block', 'important');
        modalEl.style.setProperty('visibility', 'visible', 'important');
        modalEl.style.setProperty('opacity', '1', 'important');
        modalEl.style.setProperty('pointer-events', 'auto', 'important');
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

    function showModal() {
        prepareModal();
        var instance = getModalInstance();
        if (instance) {
            try {
                instance.show();
                // If site modal lock still blocked Bootstrap, force a visible open.
                window.setTimeout(function () {
                    if (!modalEl.classList.contains('show') || modalEl.classList.contains('bns-modal-is-closed')) {
                        prepareModal();
                        showModalFallback();
                    }
                }, 40);
                return;
            } catch (e) {}
        }
        showModalFallback();
    }

    function fillFromButton(btn) {
        var name = btn.getAttribute('data-name') || 'Member';
        var n = btn.getAttribute('data-followup') || '';
        var status = btn.getAttribute('data-status') || 'Not saved yet';
        var statusValue = btn.getAttribute('data-status-value') || 'pending';
        var when = btn.getAttribute('data-when') || '';
        var note = (btn.getAttribute('data-note') || '').trim();
        var saveUrl = (btn.getAttribute('data-save-url') || '').trim();

        document.getElementById('crmRemarkModalFollowup').textContent = 'Follow-up ' + n;
        document.getElementById('crmRemarkModalTitle').textContent = name;
        document.getElementById('crmRemarkModalMeta').textContent = when
            ? status + ' · ' + when
            : status;

        if (statusSelect) {
            statusSelect.value = statusValue;
        }
        if (noteField) {
            noteField.value = note;
        }

        if (form && saveUrl) {
            form.setAttribute('action', saveUrl);
            if (saveBtn) saveBtn.hidden = false;
            if (statusSelect) statusSelect.disabled = false;
            if (noteField) noteField.disabled = false;
            if (readonly) readonly.hidden = true;
        } else if (form) {
            form.removeAttribute('action');
            if (saveBtn) saveBtn.hidden = true;
            if (statusSelect) statusSelect.disabled = true;
            if (noteField) noteField.disabled = true;
            if (readonly) readonly.hidden = false;
        }
    }

    // Delegated clicks so every 1–5 / Call button works even after table redraws.
    document.addEventListener('click', function (event) {
        var btn = event.target && event.target.closest
            ? event.target.closest('.js-crm-remark')
            : null;
        if (!btn) {
            return;
        }
        event.preventDefault();
        fillFromButton(btn);
        showModal();
    });
});
</script>
