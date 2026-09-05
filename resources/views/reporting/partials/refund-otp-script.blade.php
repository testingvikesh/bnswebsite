@php
    $csrf = $csrf ?? '';
@endphp
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || @json($csrf);

    document.querySelectorAll('.modal[data-refund-otp-url]').forEach(function (modal) {
        const form = modal.querySelector('.bns-refund-form');
        const sendBtn = modal.querySelector('.bns-refund-send-otp');
        const submitBtn = modal.querySelector('.bns-refund-submit');
        const amountInput = modal.querySelector('.bns-refund-amount');
        const otpInput = modal.querySelector('.bns-refund-otp');
        const otpStep = modal.querySelector('.bns-refund-otp-step');
        const feedback = modal.querySelector('.bns-refund-otp-feedback');
        const otpUrl = modal.getAttribute('data-refund-otp-url');

        if (!form || !sendBtn || !submitBtn || !amountInput || !otpInput || !otpStep || !feedback || !otpUrl) {
            return;
        }

        function showFeedback(message, ok) {
            feedback.textContent = message;
            feedback.classList.remove('d-none', 'alert-success', 'alert-danger');
            feedback.classList.add(ok ? 'alert-success' : 'alert-danger');
        }

        sendBtn.addEventListener('click', async function () {
            if (!amountInput.checkValidity()) {
                amountInput.reportValidity();
                return;
            }

            sendBtn.disabled = true;
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';

            try {
                const response = await fetch(otpUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        refund_amount: amountInput.value,
                    }),
                });

                const data = await response.json().catch(function () {
                    if (response.status === 419) {
                        return { success: false, message: 'Session expired. Refresh the page and try Send OTP again.' };
                    }
                    return { success: false, message: 'Unable to send OTP. Please try again.' };
                });

                if (!response.ok || !data.success) {
                    showFeedback(data.message || 'Unable to send OTP. Please try again.', false);
                    return;
                }

                showFeedback(data.message || 'OTP sent successfully.', true);
                otpStep.classList.remove('d-none');
                submitBtn.classList.remove('d-none');
                submitBtn.disabled = false;
                otpInput.required = true;
                otpInput.focus();
                amountInput.readOnly = true;
                sendBtn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Resend OTP';
            } catch (error) {
                showFeedback('Unable to send OTP. Please try again.', false);
            } finally {
                sendBtn.disabled = false;
                if (!sendBtn.innerHTML.includes('Resend')) {
                    sendBtn.innerHTML = '<i class="bi bi-envelope me-1"></i> Send OTP';
                }
            }
        });

        modal.addEventListener('hidden.bs.modal', function () {
            feedback.classList.add('d-none');
            feedback.textContent = '';
            otpStep.classList.add('d-none');
            submitBtn.classList.add('d-none');
            submitBtn.disabled = true;
            otpInput.required = false;
            otpInput.value = '';
            amountInput.readOnly = false;
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="bi bi-envelope me-1"></i> Send OTP';
        });
    });
})();
</script>
