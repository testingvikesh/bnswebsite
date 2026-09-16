@extends('layouts.front')

@section('title', 'CRM Attendance Sync')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal" data-attendance-sync data-sync-url="{{ $syncUrl }}">
    @include('partials.page-header', [
        'title' => 'Attendance Sync',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => $isAdmin ? route('crm.dashboard') : route('crm.desk')],
            ['label' => 'Attendance Sync'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @include('crm.partials.toolbar', ['active' => 'attendance-sync', 'isAdmin' => $isAdmin, 'employee' => $employee])

            <p class="bns-crm-section-copy">
                Scan the Control Panel QR into the <strong>mobile no</strong> box. The QR contains the mobile number, not a website link.
                Click <strong>Sync now</strong> to mark Present in the attendance module.
            </p>

            <div class="bns-crm-stats">
                <div class="bns-crm-stat">
                    <span>Pending on this device</span>
                    <strong data-pending-count>0</strong>
                </div>
                <div class="bns-crm-stat bns-crm-stat--present">
                    <span>Synced from this device</span>
                    <strong data-synced-count>0</strong>
                </div>
            </div>

            <div class="bns-crm-grid-2">
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Capture record</h3>
                        <span>Saved locally until you sync</span>
                    </div>
                    <form class="bns-crm-form" data-capture-form>
                        <label>Mobile no</label>
                        <input type="tel" name="mobile_no" id="crm_attendance_mobile" inputmode="numeric" maxlength="15" placeholder="Scan QR here or type mobile" autocomplete="off" autofocus>

                        <label>Register no (last 4 digits)</label>
                        <input type="text" name="register_no" inputmode="numeric" maxlength="8" placeholder="e.g. 0042">

                        <label>Email ID</label>
                        <input type="email" name="email" placeholder="registered email">

                        <label>QR from attendance email (optional)</label>
                        <input type="text" name="qr_data" placeholder="Old QR URL still works if pasted here" autocomplete="off">
                        <button type="button" class="bns-crm-mini-btn" data-scan-btn hidden>Scan QR with camera</button>

                        <button type="submit" class="bns-mail-login__submit">
                            <i class="fas fa-save" aria-hidden="true"></i> Submit to this device
                        </button>
                    </form>
                    <p class="is-muted" style="padding:0 18px 16px;" data-capture-msg></p>
                </section>

                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Local records</h3>
                        <button type="button" class="bns-crm-mini-btn bns-crm-mini-btn--primary" data-sync-btn>Sync now</button>
                    </div>
                    <p class="is-muted" style="padding:12px 18px 0;" data-sync-msg></p>
                    <div class="bns-crm-table-wrap">
                        <table class="bns-crm-table">
                            <thead>
                                <tr>
                                    <th>Saved</th>
                                    <th>Mobile / Register / Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody data-record-rows></tbody>
                        </table>
                    </div>
                </section>
            </div>

            <p class="bns-crm-section-copy">
                Android Attendance Sync app API URL:<br>
                <strong>{{ $appApiBase }}</strong>
            </p>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var root = document.querySelector('[data-attendance-sync]');
    if (!root) return;
    var STORAGE_KEY = 'bns_crm_attendance_queue';
    var DEVICE_KEY = 'bns_crm_attendance_device';
    var syncUrl = root.getAttribute('data-sync-url');
    var form = root.querySelector('[data-capture-form]');
    var rowsEl = root.querySelector('[data-record-rows]');
    var pendingEl = root.querySelector('[data-pending-count]');
    var syncedEl = root.querySelector('[data-synced-count]');
    var captureMsg = root.querySelector('[data-capture-msg]');
    var syncMsg = root.querySelector('[data-sync-msg]');
    var scanBtn = root.querySelector('[data-scan-btn]');

    function deviceId() {
        var id = localStorage.getItem(DEVICE_KEY);
        if (!id) {
            id = 'crm-web-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8);
            localStorage.setItem(DEVICE_KEY, id);
        }
        return id;
    }

    function loadAll() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); } catch (e) { return []; }
    }

    function saveAll(rows) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(rows));
        render();
    }

    function pad(n) { return String(n).padStart(2, '0'); }

    function nowStamp() {
        var d = new Date();
        return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
    }

    function render() {
        var rows = loadAll();
        var pending = rows.filter(function (r) { return !r.synced; }).length;
        var synced = rows.filter(function (r) { return r.synced; }).length;
        pendingEl.textContent = pending;
        syncedEl.textContent = synced;
        if (!rows.length) {
            rowsEl.innerHTML = '<tr><td colspan="3" class="bns-crm-empty">No local records yet.</td></tr>';
            return;
        }
        rowsEl.innerHTML = rows.slice().reverse().map(function (r) {
            var bits = [r.mobile_no, r.register_no, r.email, r.qr_data ? 'QR' : ''].filter(Boolean).join(' · ');
            return '<tr><td>' + (r.captured_at || '') + '</td><td>' + bits + '</td><td>' +
                (r.synced ? '<span class="bns-crm-badge bns-crm-badge--present">Synced</span>' : '<span class="bns-crm-badge bns-crm-badge--absent">Pending</span>') +
                '</td></tr>';
        }).join('');
    }

    function applyScannedValue(raw) {
        raw = String(raw || '').trim();
        if (!raw) {
            return;
        }
        var digits = raw.replace(/\D+/g, '');
        if (digits.length >= 10) {
            form.mobile_no.value = digits.slice(-10);
            form.qr_data.value = '';
            return;
        }
        if (/^\d{4}$/.test(digits)) {
            form.register_no.value = digits;
            form.qr_data.value = '';
            return;
        }
        form.qr_data.value = raw;
    }

    form.mobile_no.addEventListener('input', function () {
        var digits = (form.mobile_no.value || '').replace(/\D+/g, '');
        if (digits.length > 10) {
            form.mobile_no.value = digits.slice(-10);
        }
    });

    form.qr_data.addEventListener('change', function () {
        applyScannedValue(form.qr_data.value);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var qr = (form.qr_data.value || '').trim();
        var mobile = (form.mobile_no.value || '').replace(/\D+/g, '');
        var register = (form.register_no.value || '').replace(/\D+/g, '');
        var email = (form.email.value || '').trim();
        if (register.length > 4) register = register.slice(-4);
        if (mobile.length > 10) mobile = mobile.slice(-10);
        if (!qr && !mobile && !register && !email) {
            captureMsg.textContent = 'Enter register no, mobile, email, or QR.';
            return;
        }
        var rows = loadAll();
        rows.push({
            local_id: Date.now(),
            qr_data: qr,
            mobile_no: mobile,
            register_no: register,
            email: email,
            facility_name: 'CRM Desk',
            captured_at: nowStamp(),
            synced: false
        });
        saveAll(rows);
        form.reset();
        captureMsg.textContent = 'Saved on this device. Click Sync now to mark attendance.';
    });

    root.querySelector('[data-sync-btn]').addEventListener('click', function () {
        var all = loadAll();
        var pending = all.filter(function (r) { return !r.synced; });
        if (!pending.length) {
            syncMsg.textContent = 'Nothing to sync.';
            return;
        }
        syncMsg.textContent = 'Syncing ' + pending.length + ' record(s)…';
        fetch(syncUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ device_id: deviceId(), records: pending, facilities: [{ name: 'CRM Desk', code: 'CRM' }] })
        }).then(function (res) { return res.json().then(function (json) { return { res: res, json: json }; }); })
        .then(function (out) {
            var json = out.json || {};
            var syncedIds = (json.synced_local_ids || []).map(Number);
            var next = all.map(function (row) {
                if (syncedIds.indexOf(Number(row.local_id)) !== -1) {
                    row.synced = true;
                    row.server_id = (json.server_ids || {})[String(row.local_id)] || row.server_id;
                }
                return row;
            });
            saveAll(next);
            var failed = json.failed || [];
            syncMsg.textContent = json.message || 'Sync finished.';
            if (failed.length) {
                syncMsg.textContent += ' Failed: ' + failed.map(function (f) { return f.message; }).join(' | ');
            }
        }).catch(function () {
            syncMsg.textContent = 'Sync failed. Check internet and try again.';
        });
    });

    if (window.BarcodeDetector) {
        scanBtn.hidden = false;
        scanBtn.addEventListener('click', function () {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } }).then(function (stream) {
                var video = document.createElement('video');
                video.setAttribute('playsinline', 'true');
                video.srcObject = stream;
                video.play();
                var detector = new BarcodeDetector({ formats: ['qr_code'] });
                var timer = setInterval(function () {
                    detector.detect(video).then(function (codes) {
                        if (!codes.length) return;
                        applyScannedValue(codes[0].rawValue || '');
                        stream.getTracks().forEach(function (t) { t.stop(); });
                        clearInterval(timer);
                        captureMsg.textContent = 'QR scanned into mobile number. Submit to this device.';
                    }).catch(function () {});
                }, 400);
                setTimeout(function () {
                    stream.getTracks().forEach(function (t) { t.stop(); });
                    clearInterval(timer);
                }, 20000);
            }).catch(function () {
                captureMsg.textContent = 'Camera not available. Scan into the mobile number box, or paste QR text.';
            });
        });
    }

    render();
})();
</script>
@endpush
