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

    function showModal() {
        modalEl.classList.remove('bns-modal-is-closed');
        modalEl.style.removeProperty('display');
        var instance = getModalInstance();
        if (instance) {
            instance.show();
            return;
        }
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        modalEl.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');
    }

    document.querySelectorAll('.js-crm-remark').forEach(function (btn) {
        btn.addEventListener('click', function () {
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

            showModal();
        });
    });
});
</script>
