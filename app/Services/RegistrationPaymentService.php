<?php

namespace App\Services;

use App\Models\AdmissionPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class RegistrationPaymentService
{
    public function __construct(
        private IciciPaymentGatewayService $gateway,
        private PaymentSuccessMailer $paymentSuccessMailer,
    ) {}

    public function createForAdmission(Model $admission, string $formType): AdmissionPayment
    {
        $amount = $this->resolveRegistrationFee($formType);

        return AdmissionPayment::query()->create([
            'merchant_txn_no' => AdmissionPayment::generateMerchantTxnNo(),
            'payable_type' => $admission::class,
            'payable_id' => $admission->getKey(),
            'form_type' => $formType,
            'registration_number' => $admission->registration_number,
            'amount' => $amount,
            'currency_code' => config('payment.icici.currency_code', '356'),
            'customer_name' => $admission->full_name,
            'customer_email' => $admission->email,
            'customer_mobile' => $admission->mobile,
            'addl_param1' => $admission->registration_number,
            'addl_param2' => $formType,
            'status' => AdmissionPayment::STATUS_PENDING,
        ]);
    }

    public function redirectToCheckoutForIntroSession(Model $inquiry): RedirectResponse
    {
        if (! Schema::hasTable('admission_payments')) {
            return redirect()
                ->route('pay-now')
                ->with('error', 'Payment is temporarily unavailable. Please try again later.');
        }

        $payment = $this->createForAdmission($inquiry, 'intro-session');

        return redirect()
            ->route('payment.checkout', $payment->merchant_txn_no)
            ->with('info', 'Session booking found. Please complete payment for '.$inquiry->registration_number.'.');
    }

    public function latestSuccessfulForRegistration(string $registrationNumber): ?AdmissionPayment
    {
        if (! Schema::hasTable('admission_payments') || $registrationNumber === '') {
            return null;
        }

        return AdmissionPayment::query()
            ->where('registration_number', $registrationNumber)
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->latest('id')
            ->first();
    }

    public function redirectToCheckout(Model $admission, string $formType): RedirectResponse
    {
        if (! Schema::hasTable('admission_payments')) {
            return redirect()
                ->route('register')
                ->with('success', "Your admission form has been submitted. Registration Number: {$admission->registration_number}")
                ->with('active_form', $formType);
        }

        $payment = $this->createForAdmission($admission, $formType);

        return redirect()
            ->route('payment.checkout', $payment->merchant_txn_no)
            ->with('info', "Registration saved. Please complete payment for {$admission->registration_number}.");
    }

    public function initiateAndRedirect(AdmissionPayment $payment): RedirectResponse
    {
        $payment = $this->syncPendingAmount($payment);

        try {
            $result = $this->gateway->initiateSale($payment);
        } catch (\Throwable $e) {
            $payment->update([
                'status' => AdmissionPayment::STATUS_FAILED,
                'response_description' => $e->getMessage(),
            ]);

            return redirect()
                ->route('payment.failure', $payment->merchant_txn_no)
                ->with('error', $e->getMessage());
        }

        $payment->update([
            'initiate_request' => $result['request'],
            'initiate_response' => $result['response'],
            'status' => AdmissionPayment::STATUS_INITIATED,
            'tran_ctx' => $result['response']['tranCtx'] ?? null,
            'redirect_uri' => $result['response']['redirectURI'] ?? null,
        ]);

        if (! $this->gateway->isInitiateSuccess($result['response'])) {
            $gatewayMessage = (string) (
                $result['response']['responseDescription']
                ?? $result['response']['respdescription']
                ?? 'Payment initiation failed.'
            );
            $gatewayCode = (string) ($result['response']['responseCode'] ?? '');

            $payment->update([
                'status' => AdmissionPayment::STATUS_FAILED,
                'response_code' => $gatewayCode ?: null,
                'response_description' => $gatewayMessage,
            ]);

            $error = $gatewayCode !== ''
                ? "Payment could not be started ({$gatewayCode}): {$gatewayMessage}"
                : "Payment could not be started: {$gatewayMessage}";

            return redirect()
                ->route('payment.failure', $payment->merchant_txn_no)
                ->with('error', $error);
        }

        $redirectUrl = $this->gateway->redirectUrl($result['response']);

        if (! $redirectUrl) {
            $payment->update([
                'status' => AdmissionPayment::STATUS_FAILED,
                'response_description' => 'Invalid payment gateway redirect response.',
            ]);

            return redirect()
                ->route('payment.failure', $payment->merchant_txn_no)
                ->with('error', 'Invalid payment gateway response.');
        }

        return redirect()->away($redirectUrl);
    }

    /** @param array<string, mixed> $callbackData */
    public function processCallback(AdmissionPayment $payment, array $callbackData): AdmissionPayment
    {
        $payment->update(['callback_response' => $callbackData]);

        $statusResult = $this->gateway->checkStatus($payment);
        $payment->update(['status_response' => $statusResult['response']]);

        $response = array_merge($callbackData, $statusResult['response']);

        if ($this->gateway->isPaymentSuccess($response)) {
            $wasAlreadyPaid = $payment->isPaid();

            $payment->update([
                'status' => AdmissionPayment::STATUS_SUCCESS,
                'response_code' => $response['responseCode'] ?? null,
                'response_description' => $response['respdescription'] ?? ($response['responseDescription'] ?? 'Transaction successful'),
                'payment_mode' => $response['paymentMode'] ?? null,
                'payment_sub_inst_type' => $response['paymentSubInstType'] ?? null,
                'payment_id' => $response['paymentID'] ?? null,
                'txn_id' => $response['txnID'] ?? null,
                'payment_datetime' => $response['paymentDateTime'] ?? null,
                'paid_at' => $payment->paid_at ?: now(),
            ]);

            $payment->payable?->update(['status' => 'reviewing']);

            $freshPayment = $payment->fresh(['payable']);

            if (! $wasAlreadyPaid && $freshPayment) {
                $this->paymentSuccessMailer->send($freshPayment);
            }

            return $freshPayment ?? $payment->fresh();
        }

        $payment->update([
            'status' => AdmissionPayment::STATUS_FAILED,
            'response_code' => $response['responseCode'] ?? null,
            'response_description' => $response['respdescription'] ?? ($response['responseDescription'] ?? 'Payment failed'),
            'payment_mode' => $response['paymentMode'] ?? null,
            'payment_id' => $response['paymentID'] ?? null,
            'txn_id' => $response['txnID'] ?? null,
            'payment_datetime' => $response['paymentDateTime'] ?? null,
        ]);

        return $payment->fresh();
    }

    public function findByMerchantTxnNo(string $merchantTxnNo): AdmissionPayment
    {
        return AdmissionPayment::query()
            ->with('payable')
            ->where('merchant_txn_no', $merchantTxnNo)
            ->firstOrFail();
    }

    private function resolveRegistrationFee(string $formType): string
    {
        $amount = config("payment.registration_fees.{$formType}", config('payment.default_amount', '100.00'));

        return number_format((float) $amount, 2, '.', '');
    }

    public function syncPendingAmount(AdmissionPayment $payment): AdmissionPayment
    {
        if ($payment->isPaid()) {
            return $payment;
        }

        $currentAmount = $this->resolveRegistrationFee($payment->form_type);

        if ((float) $payment->amount !== (float) $currentAmount) {
            $payment->update(['amount' => $currentAmount]);

            return $payment->fresh();
        }

        return $payment;
    }
}
