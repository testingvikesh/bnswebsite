(function ($) {
    'use strict';

    function parseHideBusinessChoices($form) {
        var raw = $form.attr('data-hide-business-choices');

        if (!raw) {
            return [];
        }

        try {
            var parsed = JSON.parse(raw);

            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    }

    function selectedIntroProgramChoice($form) {
        var $selected = $form.find('input.js-audience-who-radio:checked');

        return $selected.length ? String($selected.val() || '') : '';
    }

    function introSessionBusinessShouldShow($form) {
        var $section = $form.find('.js-intro-session-business-section');

        if (!$section.length) {
            return false;
        }

        var hideChoices = parseHideBusinessChoices($form);
        var choice = selectedIntroProgramChoice($form);

        if (choice === '') {
            return false;
        }

        return hideChoices.indexOf(choice) === -1;
    }

    function introSessionBusinessRequired($form) {
        return introSessionBusinessShouldShow($form);
    }

    function clearIntroSessionBusinessFields($form) {
        var $section = $form.find('.js-intro-session-business-section');

        $section.find('input[type="text"], textarea').val('');
        $section.find('select').each(function () {
            this.selectedIndex = 0;
        });
        $section.find('.js-intro-session-other-wrap').removeClass('is-visible');
    }

    function syncIntroSessionBusinessVisibility($form) {
        if (!$form || !$form.length) {
            return;
        }

        var $section = $form.find('.js-intro-session-business-section');

        if (!$section.length) {
            return;
        }

        var shouldShow = introSessionBusinessShouldShow($form);

        $section.toggleClass('is-hidden', !shouldShow);

        $section.find('[name="business_profession_category"], [name="organization_name"], [name="business_category"], [name="products_services"]').prop('required', shouldShow);

        $section.find('.js-intro-session-category-select').each(function () {
            syncIntroSessionCategoryOther(this);
        });

        if (!shouldShow) {
            clearIntroSessionBusinessFields($form);
        }

        if ($form.data('bnsIntroValidated') && $form.validate) {
            $form.validate().resetForm();
            $section.find('.error').removeClass('error');
            $section.find('label.error').remove();
        }
    }

    function syncIntroSessionCategoryOther(select) {
        if (!$) {
            return;
        }

        var $select = $(select);
        var wrapId = $select.attr('data-other-target');

        if (!wrapId) {
            return;
        }

        var $wrap = $('#' + wrapId);
        var $input = $wrap.find('input[type="text"]');
        var isOther = $select.val() === 'Other';

        $wrap.toggleClass('is-visible', isOther);
        $input.prop('required', isOther);

        if (!isOther) {
            $input.val('');
        }
    }

    function initIntroSessionCategoryOther($scope) {
        if (!$) {
            return;
        }

        $scope.find('.js-intro-session-category-select').each(function () {
            syncIntroSessionCategoryOther(this);
        });

        if ($scope.is('form.bns-intro-session-form')) {
            syncIntroSessionBusinessVisibility($scope);
        } else {
            $scope.find('form.bns-intro-session-form').each(function () {
                syncIntroSessionBusinessVisibility($(this));
            });
        }
    }

    function bootIntroSessionCategoryOther() {
        initIntroSessionCategoryOther($(document));

        $(document).on('change', '.js-intro-session-category-select', function () {
            syncIntroSessionCategoryOther(this);
        });

        $(document).on('change', 'input.js-audience-who-radio', function () {
            var $form = $(this).closest('form');

            if ($form.hasClass('bns-intro-session-form')) {
                syncIntroSessionBusinessVisibility($form);
            }
        });

        $(document).on('change', 'input.js-intro-session-number', function () {
            var $group = $(this).closest('.bns-intro-session-modal__options');
            $group.find('.bns-intro-session-modal__option--selectable').removeClass('is-selected');
            $(this).closest('.bns-intro-session-modal__option--selectable').addClass('is-selected');
        });

        $(document).on('shown.bs.modal', '.bns-intro-session-modal', function () {
            initIntroSessionCategoryOther($(this));
        });
    }

    if ($) {
        $(bootIntroSessionCategoryOther);
    }

    function setIntroFormSubmitting($form, isSubmitting) {
        if (!$form || !$form.length) {
            return;
        }

        var formEl = $form[0];
        var $submitBtn = $form.find('.bns-intro-session-form__btn, [type="submit"]').first();
        var btnEl = $submitBtn[0];
        var labelEl = $submitBtn.find('[data-intro-btn-label]')[0];
        var loaderEl = $submitBtn.find('[data-intro-btn-loader]')[0];

        $form.toggleClass('is-submitting', !!isSubmitting);
        $submitBtn.toggleClass('is-loading', !!isSubmitting);
        $submitBtn.attr('aria-busy', isSubmitting ? 'true' : 'false');
        $submitBtn.attr('aria-disabled', isSubmitting ? 'true' : 'false');

        // Prefer attribute/DOM toggle over CSS-only — always visible.
        if (labelEl) {
            labelEl.hidden = !!isSubmitting;
            labelEl.style.display = isSubmitting ? 'none' : '';
        }
        if (loaderEl) {
            loaderEl.hidden = !isSubmitting;
            loaderEl.style.display = isSubmitting ? 'inline-flex' : 'none';
            loaderEl.setAttribute('aria-hidden', isSubmitting ? 'false' : 'true');
        }

        // Do NOT disable the submit button until after native submit —
        // disabling during the click can cancel submit in some browsers.
        // Lock with aria + CSS pointer-events instead.
        if (btnEl) {
            btnEl.style.pointerEvents = isSubmitting ? 'none' : '';
            btnEl.style.cursor = isSubmitting ? 'wait' : '';
        }
        if (formEl) {
            formEl.style.pointerEvents = isSubmitting ? 'none' : '';
        }

        if (isSubmitting && formEl) {
            // Force paint before network work.
            // eslint-disable-next-line no-unused-expressions
            formEl.offsetWidth;
        }
    }

    function bindIntroFormSubmitLock($form) {
        if (!$form.length || $form.data('bnsSubmitLockBound')) {
            return;
        }

        $form.data('bnsSubmitLockBound', true);

        // Show loader as soon as submit is attempted (including during remote mobile check).
        $form.on('submit.bnsIntroLoader', function () {
            setIntroFormSubmitting($form, true);
        });

        // Extra safety: if user double-clicks the button while locking.
        $form.on('click.bnsIntroLoader', '.bns-intro-session-form__btn', function (event) {
            if ($form.hasClass('is-submitting') || $form.data('bnsSubmitting')) {
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
        });
    }

    if ($) {
        $(function () {
            $('.bns-intro-session-form').each(function () {
                bindIntroFormSubmitLock($(this));
            });
        });
    }

    // Vanilla capture listener — runs even if jQuery validate order changes.
    if (typeof document !== 'undefined') {
        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!form || !form.classList || !form.classList.contains('bns-intro-session-form')) {
                return;
            }
            if (!form.hasAttribute('data-check-mobile-url')) {
                return;
            }
            form.classList.add('is-submitting');
            var btn = form.querySelector('.bns-intro-session-form__btn');
            if (btn) {
                btn.classList.add('is-loading');
                var label = btn.querySelector('[data-intro-btn-label]');
                var loader = btn.querySelector('[data-intro-btn-loader]');
                if (label) {
                    label.hidden = true;
                    label.style.display = 'none';
                }
                if (loader) {
                    loader.hidden = false;
                    loader.style.display = 'inline-flex';
                }
                btn.style.pointerEvents = 'none';
                btn.style.cursor = 'wait';
            }
            form.style.pointerEvents = 'none';
        }, true);
    }

    if (!$ || !$.validator) {
        return;
    }

    $.validator.addMethod('indianMobile', function (value, element) {
        var digits = String(value || '').replace(/\D/g, '');

        if (digits.length === 12 && digits.indexOf('91') === 0) {
            digits = digits.slice(2);
        } else if (digits.length > 10) {
            digits = digits.slice(-10);
        }

        return this.optional(element) || /^[6-9]\d{9}$/.test(digits);
    }, 'Please enter a valid 10-digit mobile number (without +91).');

    function currentCsrfToken($form) {
        return String(
            $form.find('input[name="_token"]').val()
            || $('meta[name="csrf-token"]').attr('content')
            || ''
        );
    }

    function applyCsrfToken($form, token) {
        if (!token) {
            return;
        }

        // Keep every form + meta tag in sync (multiple intro forms can exist on one page).
        $('input[name="_token"]').val(token);
        var $meta = $('meta[name="csrf-token"]');
        if ($meta.length) {
            $meta.attr('content', token);
        }
        if ($form && $form.length) {
            $form.find('input[name="_token"]').val(token);
        }
    }

    function nativeSubmitIntroForm(form) {
        var $form = $(form);
        setIntroFormSubmitting($form, true);

        var $btn = $form.find('.bns-intro-session-form__btn').first();
        // Safe to disable now — native submit() does not need an enabled button.
        $btn.prop('disabled', true);

        window.setTimeout(function () {
            HTMLFormElement.prototype.submit.call(form);
        }, 120);
    }

    function refreshCsrfToken($form) {
        var csrfUrl = $form.data('csrf-url') || '/csrf-token';

        return $.ajax({
            url: csrfUrl,
            type: 'GET',
            dataType: 'json',
            cache: false,
            xhrFields: {
                withCredentials: true
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(function (response) {
            var token = response && response.token ? response.token : null;
            applyCsrfToken($form, token);
            return token;
        });
    }

    function initIntroSessionForm($form) {
        if (!$form.length || $form.data('bnsIntroValidated')) {
            return;
        }

        var checkMobileUrl = $form.data('check-mobile-url');
        var checkEmailUrl = $form.data('check-email-url');
        var mobileField = $form.find('[name="mobile"]');
        var emailField = $form.find('[name="email"]');

        $form.data('bnsIntroValidated', true);
        bindIntroFormSubmitLock($form);
        initIntroSessionCategoryOther($form);
        syncIntroSessionBusinessVisibility($form);

        // Keep only 10 digits (strip +91 / spaces if browser restored old value).
        if (mobileField.length) {
            mobileField.val(normalizeTenDigitMobile(mobileField.val()));
        }

        bindTenDigitMobileField($form);

        $form.validate({
            ignore: ':hidden:not(select)',
            errorElement: 'label',
            errorClass: 'error',
            highlight: function (element) {
                $(element).addClass('error');
            },
            unhighlight: function (element) {
                $(element).removeClass('error');
            },
            invalidHandler: function () {
                $form.removeData('bnsSubmitting');
                setIntroFormSubmitting($form, false);
                $form.find('.bns-intro-session-form__btn').prop('disabled', false);
            },
            submitHandler: function (form) {
                var $readyForm = $(form);

                if ($readyForm.data('bnsSubmitting')) {
                    return false;
                }

                $readyForm.data('bnsSubmitting', true);
                setIntroFormSubmitting($readyForm, true);

                refreshCsrfToken($readyForm)
                    .done(function (token) {
                        if (!token) {
                            $readyForm.removeData('bnsSubmitting');
                            setIntroFormSubmitting($readyForm, false);
                            $readyForm.find('.bns-intro-session-form__btn').prop('disabled', false);
                            window.alert('Your session could not be refreshed. Please reload the page and try again.');
                            return;
                        }

                        // Already validated — keep loader visible, then POST.
                        nativeSubmitIntroForm(form);
                    })
                    .fail(function (xhr) {
                        $readyForm.removeData('bnsSubmitting');
                        setIntroFormSubmitting($readyForm, false);
                        $readyForm.find('.bns-intro-session-form__btn').prop('disabled', false);
                        var message = 'Your session expired. Please reload the page and submit again.';
                        if (xhr && xhr.status === 419) {
                            message = 'Page expired (419). Please reload the page and try again.';
                        }
                        window.alert(message);
                    });

                return false;
            },
            rules: (function () {
                var rules = {
                    full_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    mobile: {
                        required: true,
                        indianMobile: true,
                        maxlength: 10,
                        remote: checkMobileUrl
                            ? {
                                url: checkMobileUrl,
                                type: 'post',
                                beforeSend: function (xhr) {
                                    xhr.setRequestHeader('X-CSRF-TOKEN', currentCsrfToken($form));
                                },
                                data: {
                                    _token: function () {
                                        return currentCsrfToken($form);
                                    },
                                    mobile: function () {
                                        return mobileField.val();
                                    },
                                    form_source: function () {
                                        return $form.find('[name="form_source"]').val() || 'intro-session-modal';
                                    }
                                }
                            }
                            : false
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255,
                        remote: checkEmailUrl
                            ? {
                                url: checkEmailUrl,
                                type: 'post',
                                beforeSend: function (xhr) {
                                    xhr.setRequestHeader('X-CSRF-TOKEN', currentCsrfToken($form));
                                },
                                data: {
                                    _token: function () {
                                        return currentCsrfToken($form);
                                    },
                                    email: function () {
                                        return emailField.val();
                                    },
                                    mobile: function () {
                                        return mobileField.val();
                                    },
                                    form_source: function () {
                                        return $form.find('[name="form_source"]').val() || 'intro-session-modal';
                                    }
                                }
                            }
                            : false
                    },
                    city: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    state: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    }
                };

                if ($form.find('[name="hear_about"]').length) {
                    rules.hear_about = {
                        required: true
                    };
                    rules.hear_about_other = {
                        required: {
                            depends: function () {
                                return $form.find('[name="hear_about"]').val() === 'Other';
                            }
                        },
                        minlength: 2,
                        maxlength: 255
                    };
                }

                if ($form.find('[name="business_profession_category"]').length) {
                    rules.business_profession_category = {
                        required: {
                            depends: function () {
                                return introSessionBusinessRequired($form);
                            }
                        }
                    };
                    rules.business_profession_category_other = {
                        required: {
                            depends: function () {
                                return introSessionBusinessRequired($form)
                                    && $form.find('[name="business_profession_category"]').val() === 'Other';
                            }
                        },
                        minlength: 2,
                        maxlength: 255
                    };
                    rules.organization_name = {
                        required: {
                            depends: function () {
                                return introSessionBusinessRequired($form);
                            }
                        },
                        minlength: 2,
                        maxlength: 255
                    };
                    rules.business_category = {
                        required: {
                            depends: function () {
                                return introSessionBusinessRequired($form);
                            }
                        }
                    };
                    rules.business_category_other = {
                        required: {
                            depends: function () {
                                return introSessionBusinessRequired($form)
                                    && $form.find('[name="business_category"]').val() === 'Other';
                            }
                        },
                        minlength: 2,
                        maxlength: 255
                    };
                    rules.products_services = {
                        required: {
                            depends: function () {
                                return introSessionBusinessRequired($form);
                            }
                        },
                        minlength: 10,
                        maxlength: 2000
                    };
                }

                return rules;
            })(),
            messages: {
                full_name: {
                    required: 'Please enter your full name.',
                    minlength: 'Name must be at least 2 characters.'
                },
                mobile: {
                    required: 'Please enter your mobile number.',
                    indianMobile: 'Please enter a valid 10-digit mobile number.',
                    maxlength: 'Mobile number must be exactly 10 digits.',
                    remote: 'This mobile number is already registered.'
                },
                email: {
                    required: 'Please enter your email address.',
                    email: 'Please enter a valid email address.',
                    remote: 'This email is already registered.'
                },
                city: {
                    required: 'Please enter your city.'
                },
                state: {
                    required: 'Please enter your state.'
                },
                hear_about: {
                    required: 'Please tell us how you heard about BNS.'
                },
                hear_about_other: {
                    required: 'Please specify how you heard about BNS.',
                    minlength: 'Please enter at least 2 characters.'
                },
                business_profession_category: {
                    required: 'Please select your business / profession category.'
                },
                business_profession_category_other: {
                    required: 'Please specify your business / profession category.',
                    minlength: 'Please enter at least 2 characters.'
                },
                organization_name: {
                    required: 'Please enter your business / company / organization name.',
                    minlength: 'Please enter at least 2 characters.'
                },
                business_category: {
                    required: 'Please select your business category.'
                },
                business_category_other: {
                    required: 'Please specify your business industry / category.',
                    minlength: 'Please enter at least 2 characters.'
                },
                products_services: {
                    required: 'Please describe your products or services.',
                    minlength: 'Please describe in at least 10 characters (2–3 sentences).'
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') === 'intro_session_number') {
                    error.insertAfter(element.closest('.bns-intro-session-modal__options'));
                    return;
                }

                error.insertAfter(element);
            }
        });

        // Digit limiting handled by bindTenDigitMobileField (called above).
    }

    function normalizeTenDigitMobile(value) {
        var digits = String(value || '').replace(/\D/g, '');

        // Strip country code if pasted as +91XXXXXXXXXX / 91XXXXXXXXXX
        if (digits.length === 12 && digits.indexOf('91') === 0) {
            digits = digits.slice(2);
        } else if (digits.length > 10) {
            digits = digits.slice(-10);
        }

        return digits.slice(0, 10);
    }

    function bindTenDigitMobileField($form) {
        var mobileField = $form.find('[name="mobile"]');
        if (!mobileField.length || mobileField.data('bnsTenDigitBound')) {
            return;
        }

        mobileField.data('bnsTenDigitBound', true);
        mobileField.attr({
            maxlength: 10,
            inputmode: 'numeric',
            autocomplete: 'tel',
            placeholder: mobileField.attr('placeholder') || '10-digit mobile number'
        });

        mobileField.val(normalizeTenDigitMobile(mobileField.val()));

        mobileField.on('input.bnsTenDigit', function () {
            var digits = normalizeTenDigitMobile($(this).val());
            if ($(this).val() !== digits) {
                $(this).val(digits);
            }
        });

        mobileField.on('keypress.bnsTenDigit', function (event) {
            var key = event.which || event.keyCode;
            if (event.ctrlKey || event.metaKey || key === 8 || key === 9 || key === 13) {
                return;
            }
            // Block +, space, letters, and any non-digit (including when typing "91" prefix intent via +)
            if (key < 48 || key > 57) {
                event.preventDefault();
            }
            if (String($(this).val() || '').replace(/\D/g, '').length >= 10) {
                event.preventDefault();
            }
        });

        mobileField.on('paste.bnsTenDigit', function (event) {
            event.preventDefault();
            var pasted = '';
            try {
                pasted = (event.originalEvent || event).clipboardData.getData('text') || '';
            } catch (e) {}
            $(this).val(normalizeTenDigitMobile(pasted));
        });
    }

    $(function () {
        $('.bns-intro-session-form, .bns-register-quick-form').each(function () {
            var $form = $(this);

            bindIntroFormSubmitLock($form);
            bindTenDigitMobileField($form);
            initIntroSessionCategoryOther($form);
            syncIntroSessionBusinessVisibility($form);

            if ($form.is('[data-check-mobile-url]')) {
                initIntroSessionForm($form);
            }
        });

        $(document).on('shown.bs.modal', '.bns-intro-session-modal', function () {
            var $form = $(this).find('form.bns-intro-session-form').first();
            if ($form.length) {
                refreshCsrfToken($form);
            }
        });
    });
})(window.jQuery);
