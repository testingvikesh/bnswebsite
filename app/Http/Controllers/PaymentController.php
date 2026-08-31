<?php

namespace App\Http\Controllers;

use App\Models\MembershipUpload;
use App\Services\RegistrationPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private RegistrationPaymentService $payments) {}

    public function checkout(string $merchantTxnNo): View|RedirectResponse
    {
        $payment = $this->payments->findByMerchantTxnNo($merchantTxnNo);

        if ($payment->isPaid()) {
            return redirect()->route('payment.success', $merchantTxnNo);
        }

        $payment = $this->payments->syncPendingAmount($payment);

        return view('payment.checkout', [
            'payment' => $payment,
            'formLabel' => config("payment.form_type_map.{$payment->form_type}.label", 'Registration'),
        ]);
    }

    public function initiate(string $merchantTxnNo): RedirectResponse
    {
        $payment = $this->payments->findByMerchantTxnNo($merchantTxnNo);

        if ($payment->isPaid()) {
            return redirect()->route('payment.success', $merchantTxnNo);
        }

        return $this->payments->initiateAndRedirect($payment);
    }

    public function callback(Request $request): RedirectResponse
    {
        $merchantTxnNo = (string) ($request->input('merchantTxnNo') ?? $request->input('merchant_txn_no') ?? '');

        if ($merchantTxnNo === '') {
            return redirect()
                ->route('register')
                ->with('error', 'Invalid payment callback.');
        }

        $payment = $this->payments->findByMerchantTxnNo($merchantTxnNo);
        $payment = $this->payments->processCallback($payment, $request->all());

        if ($payment->isPaid()) {
            return redirect()
                ->route('payment.success', $merchantTxnNo)
                ->with('success', 'Payment completed successfully.');
        }

        return redirect()
            ->route('payment.failure', $merchantTxnNo)
            ->with('error', $payment->response_description ?: 'Payment was not successful.');
    }

    public function success(string $merchantTxnNo): View
    {
        $payment = $this->payments->findByMerchantTxnNo($merchantTxnNo);

        return view('payment.success', $this->paymentPageData($payment));
    }

    public function receipt(string $merchantTxnNo): View
    {
        $payment = $this->payments->findByMerchantTxnNo($merchantTxnNo);

        return view('payment.receipt', $this->paymentPageData($payment));
    }

    public function failure(string $merchantTxnNo): View
    {
        $payment = $this->payments->findByMerchantTxnNo($merchantTxnNo);

        return view('payment.failure', $this->paymentPageData($payment));
    }

    /**
     * @return array<string, mixed>
     */
    private function paymentPageData($payment): array
    {
        $formLabel = config("payment.form_type_map.{$payment->form_type}.label", 'Registration');
        $programLabel = $formLabel;

        if ($payment->form_type === 'intro-session') {
            $programs = config('pay_now.programs', []);
            $inquiryProgram = $payment->payable->interested_program ?? null;

            if ($inquiryProgram && isset($programs[$inquiryProgram])) {
                $programLabel = $programs[$inquiryProgram];
            } elseif ($inquiryProgram) {
                $programLabel = $inquiryProgram;
            }
        }

        return [
            'payment' => $payment,
            'formLabel' => $formLabel,
            'programLabel' => $programLabel,
            'membershipAlreadyUploaded' => $payment->form_type === 'intro-session'
                && (bool) MembershipUpload::findExistingActive($payment->registration_number),
        ];
    }
}
