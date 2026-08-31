(function () {
    'use strict';

    function initAttendance() {
        var root = document.querySelector('[data-attendance]');
        if (!root) {
            return;
        }

        var lookupUrl = root.getAttribute('data-lookup-url');
        var markUrl = root.getAttribute('data-mark-url');
        var registerUrl = root.getAttribute('data-register-url');
        var csrf = root.getAttribute('data-csrf');

        var lookupModal = document.getElementById('bnsAttendanceLookupModal');
        var lookupForm = document.getElementById('bnsAttendanceLookupForm');
        var lookupTypeInput = document.getElementById('attendance_lookup_type');
        var lookupMsg = document.getElementById('bnsAttendanceLookupMsg');
        var resultsWrap = document.getElementById('bnsAttendanceResults');
        var resultsList = document.getElementById('bnsAttendanceResultsList');
        var lookupBtn = document.getElementById('bnsAttendanceLookupBtn');
        var lookupPanel = document.getElementById('bnsAttendanceLookupPanel');
        var registerPanel = document.getElementById('bnsAttendanceRegisterPanel');
        var walkinCta = document.getElementById('bnsAttendanceWalkinCta');
        var openRegisterBtn = document.getElementById('bnsAttendanceOpenRegisterBtn');
        var backToLookupBtn = document.getElementById('bnsAttendanceBackToLookupBtn');
        var registerForm = document.getElementById('bnsAttendanceRegisterForm');
        var registerMsg = document.getElementById('bnsAttendanceRegisterMsg');

        function setLookupTab(type) {
            type = type || 'reference';

            if (lookupModal) {
                lookupModal.querySelectorAll('[data-attendance-tab]').forEach(function (el) {
                    el.classList.toggle('is-active', el.getAttribute('data-attendance-tab') === type);
                });

                lookupModal.querySelectorAll('[data-attendance-panel]').forEach(function (panel) {
                    var active = panel.getAttribute('data-attendance-panel') === type;
                    panel.hidden = !active;
                    panel.style.display = active ? '' : 'none';
                    panel.classList.toggle('is-active-panel', active);
                });
            }

            if (lookupTypeInput) {
                lookupTypeInput.value = type;
            }
            if (lookupMsg) {
                lookupMsg.hidden = true;
            }
        }

        function showLookupView() {
            if (lookupPanel) {
                lookupPanel.hidden = false;
            }
            if (registerPanel) {
                registerPanel.hidden = true;
            }
            if (lookupModal) {
                var title = document.getElementById('bnsAttendanceLookupModalLabel');
                if (title) {
                    title.textContent = 'Find Your Session Booking';
                }
            }
        }

        function showRegisterView() {
            if (lookupPanel) {
                lookupPanel.hidden = true;
            }
            if (registerPanel) {
                registerPanel.hidden = false;
            }
            if (walkinCta) {
                walkinCta.hidden = true;
            }
            if (registerMsg) {
                registerMsg.hidden = true;
            }
            if (lookupModal) {
                var title = document.getElementById('bnsAttendanceLookupModalLabel');
                if (title) {
                    title.textContent = 'Book your spot now';
                }
            }

            var firstInput = registerForm ? registerForm.querySelector('input[name="full_name"]') : null;
            if (firstInput) {
                setTimeout(function () {
                    firstInput.focus();
                }, 50);
            }
        }

        function showWalkinCta(show) {
            if (!walkinCta) {
                return;
            }
            walkinCta.hidden = !show;
        }

        function syncRegisterProgramRadios() {
            if (!registerForm) {
                return;
            }

            var programIdInput = registerForm.querySelector('.bns-quick-register-form__program-id');
            var interestedProgramInput = registerForm.querySelector('.bns-quick-register-form__interested-program');
            var categoryInput = registerForm.querySelector('.bns-quick-register-form__category');
            var messageInput = registerForm.querySelector('.bns-quick-register-form__message');
            var radios = registerForm.querySelectorAll('input[name="register_program_choice"]');

            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (!radio.checked) {
                        return;
                    }
                    if (programIdInput) {
                        programIdInput.value = radio.value;
                    }
                    var contactProgram = radio.getAttribute('data-contact-program');
                    var contactCategory = radio.getAttribute('data-contact-category');
                    var programTitle = radio.getAttribute('data-program-title');
                    if (contactProgram && interestedProgramInput) {
                        interestedProgramInput.value = contactProgram;
                    }
                    if (contactCategory && categoryInput) {
                        categoryInput.value = contactCategory;
                    }
                    if (programTitle && messageInput) {
                        messageInput.value = 'Introduction session admission request via Attendance walk-in (' + programTitle + ').';
                    }
                });
            });
        }

        function runLookup() {
            var type = lookupTypeInput ? lookupTypeInput.value : 'reference';
            var payload = { lookup_type: type };

            if (type === 'reference') {
                payload.reference_last4 = (document.getElementById('attendance_reference_last4') || {}).value || '';
            } else if (type === 'mobile') {
                payload.mobile = (document.getElementById('attendance_lookup_mobile') || {}).value || '';
            } else {
                payload.email = (document.getElementById('attendance_lookup_email') || {}).value || '';
            }

            if (lookupBtn) {
                lookupBtn.disabled = true;
                lookupBtn.textContent = 'Searching...';
            }
            if (lookupMsg) {
                lookupMsg.hidden = true;
            }
            if (resultsWrap) {
                resultsWrap.hidden = true;
            }
            if (resultsList) {
                resultsList.innerHTML = '';
            }
            showWalkinCta(false);

            fetch(lookupUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data };
                    });
                })
                .then(function (result) {
                    if (!result.ok || !result.data.ok) {
                        if (lookupMsg) {
                            lookupMsg.hidden = false;
                            lookupMsg.className = 'bns-pay-now__lookup-msg is-error';
                            lookupMsg.textContent = (result.data && result.data.message) || 'No booking found.';
                        }
                        showWalkinCta(true);
                        return;
                    }

                    var records = result.data.records || [];
                    if (!records.length) {
                        if (lookupMsg) {
                            lookupMsg.hidden = false;
                            lookupMsg.className = 'bns-pay-now__lookup-msg is-error';
                            lookupMsg.textContent = 'No booking found.';
                        }
                        showWalkinCta(true);
                        return;
                    }

                    if (lookupMsg) {
                        lookupMsg.hidden = false;
                        lookupMsg.className = 'bns-pay-now__lookup-msg is-ok';
                        lookupMsg.textContent = records.length + ' booking(s) found.';
                    }

                    records.forEach(function (record) {
                        resultsList.appendChild(buildResultCard(record));
                    });
                    resultsWrap.hidden = false;
                    showWalkinCta(false);
                })
                .catch(function () {
                    if (lookupMsg) {
                        lookupMsg.hidden = false;
                        lookupMsg.className = 'bns-pay-now__lookup-msg is-error';
                        lookupMsg.textContent = 'Something went wrong. Please try again.';
                    }
                    showWalkinCta(true);
                })
                .finally(function () {
                    if (lookupBtn) {
                        lookupBtn.disabled = false;
                        lookupBtn.textContent = 'Attendance Confirm';
                    }
                });
        }

        function buildResultCard(record) {
            var card = document.createElement('div');
            card.className = 'bns-pay-now__result-card';
            card.setAttribute('data-inquiry-id', record.id);

            var actionHtml = '';
            if (record.already_attended) {
                actionHtml =
                    '<p class="mb-0 text-success fw-bold"><i class="fas fa-check-circle"></i> Attendance already marked' +
                    (record.attended_at ? ' on ' + escapeHtml(record.attended_at) : '') +
                    '.</p>';
            } else {
                actionHtml =
                    '<button type="button" class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary" data-mark-attendance>' +
                    '<i class="fas fa-user-check"></i> Mark Attendance' +
                    '</button>' +
                    '<p class="bns-pay-now__update-msg" data-mark-msg hidden></p>';
            }

            card.innerHTML =
                '<p class="bns-pay-now__result-ref"><strong>Reference:</strong> ' + escapeHtml(record.registration_number || '—') + '</p>' +
                '<div class="bns-attendance__details">' +
                    '<div><span>Name</span><strong>' + escapeHtml(record.full_name || '—') + '</strong></div>' +
                    '<div><span>Mobile</span><strong>' + escapeHtml(record.mobile || '—') + '</strong></div>' +
                    '<div><span>Email</span><strong>' + escapeHtml(record.email || '—') + '</strong></div>' +
                    '<div><span>Program</span><strong>' + escapeHtml(record.program || '—') + '</strong></div>' +
                    '<div><span>Session</span><strong>' + escapeHtml(record.session_label || ('Session ' + (record.session_number || 1))) + '</strong></div>' +
                    '<div><span>Payment</span><strong>' + (record.already_paid ? 'Paid' : 'Pending') + '</strong></div>' +
                '</div>' +
                '<div class="bns-pay-now__edit-actions mt-3">' + actionHtml + '</div>';

            var markBtn = card.querySelector('[data-mark-attendance]');
            if (markBtn) {
                markBtn.addEventListener('click', function () {
                    markAttendance(card, record.id, markBtn);
                });
            }

            return card;
        }

        function markAttendance(card, inquiryId, markBtn) {
            var msg = card.querySelector('[data-mark-msg]');
            markBtn.disabled = true;
            markBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
            if (msg) {
                msg.hidden = true;
            }

            fetch(markUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ inquiry_id: inquiryId })
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data };
                    });
                })
                .then(function (result) {
                    if (!result.ok || !result.data.ok) {
                        if (msg) {
                            msg.hidden = false;
                            msg.className = 'bns-pay-now__update-msg is-error';
                            msg.textContent = (result.data && result.data.message) || 'Unable to mark attendance.';
                        }
                        markBtn.disabled = false;
                        markBtn.innerHTML = '<i class="fas fa-user-check"></i> Mark Attendance';
                        return;
                    }

                    var actions = card.querySelector('.bns-pay-now__edit-actions');
                    if (actions) {
                        actions.innerHTML =
                            '<p class="mb-0 text-success fw-bold"><i class="fas fa-check-circle"></i> ' +
                            escapeHtml(result.data.message || 'Attendance marked successfully.') +
                            '</p>';
                    }
                })
                .catch(function () {
                    if (msg) {
                        msg.hidden = false;
                        msg.className = 'bns-pay-now__update-msg is-error';
                        msg.textContent = 'Something went wrong. Please try again.';
                    }
                    markBtn.disabled = false;
                    markBtn.innerHTML = '<i class="fas fa-user-check"></i> Mark Attendance';
                });
        }

        function submitRegisterAndMark(event) {
            event.preventDefault();
            event.stopPropagation();

            if (!registerForm || !registerUrl) {
                return;
            }

            var submitBtn = registerForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Submitting...';
            }
            if (registerMsg) {
                registerMsg.hidden = true;
            }

            var formData = new FormData(registerForm);

            fetch(registerUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, status: response.status, data: data };
                    }).catch(function () {
                        return { ok: false, status: response.status, data: {} };
                    });
                })
                .then(function (result) {
                    if (!result.ok || !result.data.ok) {
                        var message = (result.data && result.data.message) || 'Unable to register and confirm attendance.';
                        if (result.data && result.data.errors) {
                            var firstKey = Object.keys(result.data.errors)[0];
                            if (firstKey && result.data.errors[firstKey] && result.data.errors[firstKey][0]) {
                                message = result.data.errors[firstKey][0];
                            }
                        }
                        if (registerMsg) {
                            registerMsg.hidden = false;
                            registerMsg.className = 'bns-pay-now__lookup-msg is-error mt-3';
                            registerMsg.textContent = message;
                        }
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = 'Register & Confirm Attendance <span class="fas fa-arrow-right"></span>';
                        }
                        return;
                    }

                    if (registerMsg) {
                        registerMsg.hidden = false;
                        registerMsg.className = 'bns-pay-now__lookup-msg is-ok mt-3';
                        registerMsg.textContent =
                            (result.data.message || 'Registration and attendance confirmed.') +
                            (result.data.registration_number ? ' Reference: ' + result.data.registration_number : '');
                    }

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Confirmed';
                    }

                    registerForm.querySelectorAll('input, select, button').forEach(function (el) {
                        if (el.type !== 'hidden') {
                            el.disabled = true;
                        }
                    });
                })
                .catch(function () {
                    if (registerMsg) {
                        registerMsg.hidden = false;
                        registerMsg.className = 'bns-pay-now__lookup-msg is-error mt-3';
                        registerMsg.textContent = 'Something went wrong. Please try again.';
                    }
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Register & Confirm Attendance <span class="fas fa-arrow-right"></span>';
                    }
                });
        }

        function escapeHtml(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function applyQueryParams() {
            var params = new URLSearchParams(window.location.search || '');
            var type = params.get('lookup_type') || '';
            var reference = params.get('reference_last4') || '';
            var mobile = params.get('mobile') || '';
            var email = params.get('email') || '';

            if (!type) {
                if (mobile) {
                    type = 'mobile';
                } else if (email) {
                    type = 'email';
                } else if (reference) {
                    type = 'reference';
                }
            }

            if (!type) {
                return false;
            }

            setLookupTab(type);

            if (type === 'reference' && reference) {
                var refInput = document.getElementById('attendance_reference_last4');
                if (refInput) {
                    refInput.value = reference;
                }
            }
            if (type === 'mobile' && mobile) {
                var mobileInput = document.getElementById('attendance_lookup_mobile');
                if (mobileInput) {
                    mobileInput.value = mobile;
                }
            }
            if (type === 'email' && email) {
                var emailInput = document.getElementById('attendance_lookup_email');
                if (emailInput) {
                    emailInput.value = email;
                }
            }

            if (window.history && window.history.replaceState) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            return true;
        }

        if (lookupModal) {
            if (lookupModal.parentElement !== document.body) {
                document.body.appendChild(lookupModal);
            }

            lookupModal.addEventListener('click', function (event) {
                var tab = event.target.closest('[data-attendance-tab]');
                if (!tab || !lookupModal.contains(tab)) {
                    return;
                }
                event.preventDefault();
                event.stopPropagation();
                setLookupTab(tab.getAttribute('data-attendance-tab'));
            });

            lookupModal.addEventListener('show.bs.modal', function () {
                document.body.classList.add('bns-membership-modal-open');
            });

            lookupModal.addEventListener('shown.bs.modal', function () {
                var type = (lookupTypeInput && lookupTypeInput.value) || 'reference';
                setLookupTab(type);
                var activePanel = lookupModal.querySelector('[data-attendance-panel="' + type + '"]');
                var firstInput = activePanel ? activePanel.querySelector('input') : null;
                if (firstInput && !(registerPanel && !registerPanel.hidden)) {
                    firstInput.focus();
                }
            });

            lookupModal.addEventListener('hidden.bs.modal', function () {
                document.body.classList.remove('bns-membership-modal-open');
                showLookupView();
            });
        }

        if (lookupForm) {
            lookupForm.addEventListener('submit', function (event) {
                event.preventDefault();
                event.stopPropagation();
                runLookup();
            });
        }

        if (lookupBtn) {
            lookupBtn.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                runLookup();
            });
        }

        if (openRegisterBtn) {
            openRegisterBtn.addEventListener('click', function (event) {
                event.preventDefault();
                showRegisterView();
            });
        }

        if (backToLookupBtn) {
            backToLookupBtn.addEventListener('click', function (event) {
                event.preventDefault();
                showLookupView();
            });
        }

        if (registerForm) {
            registerForm.addEventListener('submit', submitRegisterAndMark);
            syncRegisterProgramRadios();
        }

        var shouldAutoLookup = applyQueryParams();
        setLookupTab((lookupTypeInput && lookupTypeInput.value) || 'reference');
        showLookupView();

        try {
            if (lookupModal && lookupModal.getAttribute('data-open-on-load') === '1' && window.bootstrap && window.bootstrap.Modal) {
                window.bootstrap.Modal.getOrCreateInstance(lookupModal).show();
            }
        } catch (e) {
            // Keep lookup usable even if modal bootstrap fails.
        }

        if (shouldAutoLookup) {
            setTimeout(runLookup, 350);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAttendance);
    } else {
        initAttendance();
    }
})();
