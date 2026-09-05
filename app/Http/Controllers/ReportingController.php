<?php

namespace App\Http\Controllers;

use App\Mail\RefundOtpMail;
use App\Models\AdmissionPayment;
use App\Models\ContactInquiry;
use App\Models\MembershipUpload;
use App\Models\SessionAttendance;
use App\Models\User;
use App\Services\IciciPaymentGatewayService;
use App\Services\OutboundMailer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ReportingController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            $this->ensureReportingAccess();

            return $this->dashboard($request);
        }

        return view('reporting.login');
    }

    public function login(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('reporting.index');
        }

        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');
        $login = trim($credentials['username']);

        $user = User::query()
            ->where('email', $login)
            ->orWhere('name', $login)
            ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'These credentials do not match our records.']);
        }

        if (! $this->isBcryptHash($this->storedPassword($user))) {
            if (hash_equals($this->storedPassword($user), $credentials['password'])) {
                if (! $user->isSopAdmin()) {
                    return back()
                        ->withInput($request->only('username', 'remember'))
                        ->withErrors(['username' => 'You do not have permission to access reporting.']);
                }

                $user->password = $credentials['password'];
                $user->save();
                Auth::login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended(route('reporting.index'));
            }

            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'These credentials do not match our records.']);
        }

        if (! Auth::attempt(['email' => $user->email, 'password' => $credentials['password']], $remember)) {
            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'These credentials do not match our records.']);
        }

        $request->session()->regenerate();

        if (! Auth::user()?->isSopAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'You do not have permission to access reporting.']);
        }

        return redirect()->intended(route('reporting.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('reporting.index')
            ->with('status', 'You have been logged out successfully.');
    }

    public function show(ContactInquiry $inquiry): View
    {
        $this->ensureReportingAccess();

        return view('reporting.show', [
            'inquiry' => $inquiry,
            'formSourceLabel' => $inquiry->formSourceLabel(),
        ]);
    }

    public function payments(Request $request): View
    {
        $this->ensureReportingAccess();

        $filters = $this->paymentFiltersFromRequest($request);
        $payments = $this->filteredSuccessfulPayments($filters);
        $allSuccessful = AdmissionPayment::query()
            ->with('payable')
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->latest('paid_at')
            ->get()
            ->map(fn (AdmissionPayment $payment) => $this->decoratePayment($payment));

        $programSummary = $allSuccessful
            ->groupBy('display_program')
            ->map(fn (Collection $rows, string $program) => [
                'program' => $program,
                'count' => $rows->count(),
                'amount' => $rows->sum(fn (AdmissionPayment $payment) => (float) $payment->amount),
            ])
            ->sortByDesc('amount')
            ->values();

        $dateChips = $allSuccessful
            ->filter(fn (AdmissionPayment $payment) => $payment->paid_at !== null)
            ->groupBy(fn (AdmissionPayment $payment) => $payment->paid_at->timezone('Asia/Kolkata')->toDateString())
            ->map(fn (Collection $rows, string $ymd) => [
                'date' => $ymd,
                'label' => $rows->first()->paid_at->timezone('Asia/Kolkata')->format('d M Y'),
                'count' => $rows->count(),
            ])
            ->sortByDesc('date')
            ->values();

        $membershipsByReg = $this->membershipsByRegistration(
            $payments->pluck('registration_number')->filter()->unique()->values()->all()
        );

        return view('reporting.payments', [
            'payments' => $payments,
            'programSummary' => $programSummary,
            'dateChips' => $dateChips,
            'paidDate' => $filters['paid_date'],
            'membershipsByReg' => $membershipsByReg,
            'canManageRefunds' => (bool) Auth::user()?->isBnsVerifier(),
            'defaultRefundAmount' => (float) config('pay_now.scholarship_amount', 3160),
            'programOptions' => $allSuccessful->pluck('display_program')->filter()->unique()->sort()->values(),
            'search' => $filters['search'],
            'programFilter' => $filters['program'],
            'paymentModeFilter' => $filters['payment_mode'],
            'dateFrom' => $filters['date_from'],
            'dateTo' => $filters['date_to'],
            'stats' => [
                'total' => $allSuccessful->count(),
                'amount' => $allSuccessful->sum(fn (AdmissionPayment $payment) => (float) $payment->amount),
                'today' => $allSuccessful->filter(
                    fn (AdmissionPayment $payment) => $payment->paid_at?->isToday()
                )->count(),
                'programs' => $allSuccessful->pluck('display_program')->filter()->unique()->count(),
                'filtered' => $payments->count(),
                'filtered_amount' => $payments->sum(fn (AdmissionPayment $payment) => (float) $payment->amount),
            ],
        ]);
    }

    public function exportPayments(Request $request): StreamedResponse
    {
        $this->ensureReportingAccess();

        $payments = $this->filteredSuccessfulPayments($this->paymentFiltersFromRequest($request));
        $filename = 'bns-successful-payments-'.now()->format('Y-m-d-His').'.xls';
        $headers = [
            'Paid Date',
            'Paid Time',
            'Program',
            'Registration Number',
            'Name',
            'Email',
            'Mobile',
            'Amount',
            'Currency',
            'Payment Mode',
            'Merchant Transaction No.',
            'Gateway Transaction ID',
            'Payment ID',
            'Response Code',
            'Response Description',
            'Status',
        ];

        return response()->streamDownload(function () use ($payments, $headers) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->paid_at?->format('d M Y') ?? '',
                    $payment->paid_at?->format('h:i A') ?? '',
                    $payment->display_program,
                    $payment->registration_number,
                    $payment->customer_name,
                    $payment->customer_email,
                    $payment->customer_mobile,
                    number_format((float) $payment->amount, 2, '.', ''),
                    $payment->currency_code,
                    $payment->payment_mode,
                    $payment->merchant_txn_no,
                    $payment->txn_id,
                    $payment->payment_id,
                    $payment->response_code,
                    $payment->response_description,
                    $payment->statusLabel(),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function membership(Request $request): View
    {
        $this->ensureReportingAccess();

        $filters = $this->membershipFiltersFromRequest($request);
        $uploads = $this->filteredMembershipUploads($filters);
        $paymentsByReg = $this->successfulPaymentsByRegistration(
            $uploads->pluck('registration_number')->filter()->unique()->values()->all()
        );

        return view('reporting.membership', [
            'uploads' => $uploads,
            'paymentsByReg' => $paymentsByReg,
            'search' => $filters['search'],
            'statusFilter' => $filters['status'],
            'dateFrom' => $filters['date_from'],
            'dateTo' => $filters['date_to'],
            'statusOptions' => MembershipUpload::statusOptions(),
            'canManageBnsVerify' => (bool) Auth::user()?->isBnsVerifier(),
            'defaultRefundAmount' => (float) config('pay_now.scholarship_amount', 3160),
            'stats' => [
                'total' => MembershipUpload::query()->count(),
                'pending' => MembershipUpload::query()->where('status', MembershipUpload::STATUS_PENDING)->count(),
                'trustee_verified' => MembershipUpload::query()->where('status', MembershipUpload::STATUS_TRUSTEE_VERIFIED)->count(),
                'verified' => MembershipUpload::query()->where('status', MembershipUpload::STATUS_VERIFIED)->count(),
                'rejected' => MembershipUpload::query()->where('status', MembershipUpload::STATUS_REJECTED)->count(),
                'refunded' => MembershipUpload::query()->where('status', MembershipUpload::STATUS_REFUNDED)->count(),
                'filtered' => $uploads->count(),
            ],
        ]);
    }

    public function exportMembership(Request $request): View
    {
        $this->ensureReportingAccess();

        $filters = $this->membershipFiltersFromRequest($request);
        $uploads = $this->filteredMembershipUploads($filters);

        return view('reporting.membership-pdf', [
            'uploads' => $uploads,
            'filters' => $filters,
            'generatedAt' => now(),
            'stats' => [
                'total' => $uploads->count(),
                'pending' => $uploads->where('status', MembershipUpload::STATUS_PENDING)->count(),
                'trustee_verified' => $uploads->where('status', MembershipUpload::STATUS_TRUSTEE_VERIFIED)->count(),
                'verified' => $uploads->where('status', MembershipUpload::STATUS_VERIFIED)->count(),
                'rejected' => $uploads->where('status', MembershipUpload::STATUS_REJECTED)->count(),
                'refunded' => $uploads->where('status', MembershipUpload::STATUS_REFUNDED)->count(),
            ],
        ]);
    }

    public function trusteeVerifyMembership(Request $request, MembershipUpload $membershipUpload): RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! $membershipUpload->canTrusteeVerify()) {
            return back()->withErrors(['trustee' => 'This membership record is not awaiting trustee verification.']);
        }

        $validated = $request->validate([
            'trustee_action' => ['required', 'in:approved,rejected'],
            'trustee_remarks' => ['required', 'string', 'max:2000'],
        ]);

        $approved = $validated['trustee_action'] === MembershipUpload::STEP_APPROVED;

        $membershipUpload->update([
            'trustee_status' => $validated['trustee_action'],
            'trustee_remarks' => $validated['trustee_remarks'],
            'trustee_verified_by' => Auth::id(),
            'trustee_verified_at' => now(),
            'status' => $approved
                ? MembershipUpload::STATUS_TRUSTEE_VERIFIED
                : MembershipUpload::STATUS_REJECTED,
            'notes' => $validated['trustee_remarks'],
        ]);

        return back()->with(
            'status',
            $approved
                ? 'Trustee verification completed. Record is ready for BNS verification.'
                : 'Membership record rejected by trustee.'
        );
    }

    public function bnsVerifyMembership(Request $request, MembershipUpload $membershipUpload): RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! Auth::user()?->isBnsVerifier()) {
            abort(403, 'Only the BNS login can perform BNS verification.');
        }

        if (! $membershipUpload->canBnsVerify()) {
            return back()->withErrors(['bns' => 'BNS verification is available only after trustee approval.']);
        }

        $validated = $request->validate([
            'bns_action' => ['required', 'in:approved,rejected'],
            'bns_remarks' => ['required', 'string', 'max:2000'],
        ]);

        $status = $validated['bns_action'] === MembershipUpload::STEP_APPROVED
            ? MembershipUpload::STATUS_VERIFIED
            : MembershipUpload::STATUS_REJECTED;

        $membershipUpload->update([
            'bns_status' => $validated['bns_action'],
            'bns_remarks' => $validated['bns_remarks'],
            'bns_verified_by' => Auth::id(),
            'bns_verified_at' => now(),
            'status' => $status,
            'notes' => $validated['bns_remarks'],
        ]);

        $message = $validated['bns_action'] === MembershipUpload::STEP_APPROVED
            ? 'BNS verification completed successfully.'
            : 'Membership record rejected by BNS.';

        return back()->with('status', $message);
    }

    public function sendMembershipRefundOtp(Request $request, MembershipUpload $membershipUpload): JsonResponse|RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! Auth::user()?->isBnsVerifier()) {
            abort(403, 'Only the BNS login can process refunds.');
        }

        if (! $membershipUpload->canRefund()) {
            return $this->refundOtpResponse($request, false, 'Refund is available only after BNS verification is approved.', 422);
        }

        $payment = $membershipUpload->successfulPayment();
        if (! $payment) {
            return $this->refundOtpResponse($request, false, 'No successful payment was found for this membership registration.', 422);
        }

        if ($payment->isRefunded()) {
            return $this->refundOtpResponse($request, false, 'This payment has already been refunded. Use Check Refund Status.', 422);
        }

        $maxAmount = (float) $payment->amount;
        $validated = $request->validate([
            'refund_amount' => ['required', 'numeric', 'min:1', 'max:'.$maxAmount],
        ]);

        $refundAmount = number_format((float) $validated['refund_amount'], 2, '.', '');
        $otpLength = max(4, (int) config('reporting.refund_otp.length', 6));
        $ttlMinutes = max(1, (int) config('reporting.refund_otp.ttl_minutes', 10));
        $otpEmail = (string) config('reporting.refund_otp.email', 'mrupani2005@gmail.com');
        $otp = str_pad((string) random_int(0, (10 ** $otpLength) - 1), $otpLength, '0', STR_PAD_LEFT);

        Cache::put($this->refundOtpCacheKey($payment), [
            'hash' => Hash::make($otp),
            'amount' => $refundAmount,
            'attempts' => 0,
        ], now()->addMinutes($ttlMinutes));

        try {
            app(OutboundMailer::class)->send($otpEmail, new RefundOtpMail(
                $otp,
                (string) $membershipUpload->membership_name,
                (string) ($membershipUpload->membership_no ?: ''),
                (string) ($membershipUpload->registration_number ?: ''),
                (string) ($membershipUpload->mobile ?: ''),
                $refundAmount,
                $ttlMinutes,
            ));
        } catch (Throwable $exception) {
            Cache::forget($this->refundOtpCacheKey($payment));
            report($exception);
            Log::error('Refund OTP email failed', [
                'email' => $otpEmail,
                'membership_id' => $membershipUpload->id,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'from' => config('mail.from.address'),
                'message' => $exception->getMessage(),
            ]);

            return $this->refundOtpResponse(
                $request,
                false,
                'Unable to send OTP to '.$otpEmail.'. '.$exception->getMessage(),
                500
            );
        }

        Log::info('Refund OTP email accepted by mailer', [
            'email' => $otpEmail,
            'membership_id' => $membershipUpload->id,
            'mailer' => config('mail.default'),
            'from' => config('mail.from.address'),
        ]);

        return $this->refundOtpResponse(
            $request,
            true,
            'OTP sent to '.$otpEmail.'. Enter the OTP to complete the refund.'
        );
    }

    public function refundMembershipPayment(Request $request, MembershipUpload $membershipUpload): RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! Auth::user()?->isBnsVerifier()) {
            abort(403, 'Only the BNS login can process refunds.');
        }

        if (! $membershipUpload->canRefund()) {
            return back()->withErrors(['refund' => 'Refund is available only after BNS verification is approved.']);
        }

        $payment = $membershipUpload->successfulPayment();
        if (! $payment) {
            return back()->withErrors(['refund' => 'No successful payment was found for this membership registration.']);
        }

        if ($payment->isRefunded()) {
            return back()->withErrors(['refund' => 'This payment has already been refunded.']);
        }

        $maxAmount = (float) $payment->amount;

        $validated = $request->validate([
            'refund_amount' => ['required', 'numeric', 'min:1', 'max:'.$maxAmount],
            'otp' => ['required', 'digits:'.max(4, (int) config('reporting.refund_otp.length', 6))],
        ]);

        $refundAmount = number_format((float) $validated['refund_amount'], 2, '.', '');
        $otpPayload = Cache::get($this->refundOtpCacheKey($payment));

        if (! is_array($otpPayload) || empty($otpPayload['hash'])) {
            return back()->withErrors(['refund' => 'OTP expired or not sent. Please click Send OTP again.']);
        }

        $attempts = (int) ($otpPayload['attempts'] ?? 0);
        if ($attempts >= 5) {
            Cache::forget($this->refundOtpCacheKey($payment));

            return back()->withErrors(['refund' => 'Too many invalid OTP attempts. Please request a new OTP.']);
        }

        if (! Hash::check((string) $validated['otp'], (string) $otpPayload['hash'])) {
            $otpPayload['attempts'] = $attempts + 1;
            Cache::put(
            $this->refundOtpCacheKey($payment),
                $otpPayload,
                now()->addMinutes(max(1, (int) config('reporting.refund_otp.ttl_minutes', 10)))
            );

            return back()->withErrors(['refund' => 'Invalid OTP. Please check the email and try again.']);
        }

        if (($otpPayload['amount'] ?? null) !== $refundAmount) {
            return back()->withErrors(['refund' => 'Refund amount changed after OTP was sent. Please send a new OTP.']);
        }

        Cache::forget($this->refundOtpCacheKey($payment));

        $refundTxnNo = AdmissionPayment::generateRefundMerchantTxnNo();

        try {
            $gateway = app(IciciPaymentGatewayService::class);
            $result = $gateway->refund($payment, $refundAmount, $refundTxnNo);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['refund' => $exception->getMessage()]);
        }

        $response = $result['response'] ?? [];
        $success = app(IciciPaymentGatewayService::class)->isRefundSuccess($response);

        $payment->update([
            'refund_merchant_txn_no' => $refundTxnNo,
            'refund_amount' => $refundAmount,
            'refund_status' => $success
                ? AdmissionPayment::REFUND_STATUS_SUCCESS
                : AdmissionPayment::REFUND_STATUS_FAILED,
            'refund_response_code' => (string) ($response['responseCode'] ?? ''),
            'refund_response_description' => (string) ($response['responseDescription'] ?? ($response['respdescription'] ?? ($response['error'] ?? ''))),
            'refund_request' => $result['request'] ?? null,
            'refund_response' => $response,
            'refunded_at' => $success ? now() : null,
        ]);

        if ($success) {
            $membershipUpload->update([
                'status' => MembershipUpload::STATUS_REFUNDED,
                'bns_status' => MembershipUpload::STEP_REFUNDED,
                'notes' => trim(($membershipUpload->notes ? $membershipUpload->notes."\n" : '').'Refund of ₹'.$refundAmount.' processed.'),
            ]);

            return back()->with('status', 'Refund of ₹'.$refundAmount.' submitted successfully. Txn: '.$refundTxnNo);
        }

        $description = (string) ($response['responseDescription'] ?? ($response['respdescription'] ?? ($response['error'] ?? 'Refund request failed.')));

        return back()->withErrors(['refund' => 'Refund failed: '.$description]);
    }

    public function checkMembershipRefundStatus(Request $request, MembershipUpload $membershipUpload): RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! Auth::user()?->isBnsVerifier()) {
            abort(403, 'Only the BNS login can check refund status.');
        }

        $payment = $membershipUpload->successfulPayment();
        if (! $payment || ! $payment->refund_merchant_txn_no) {
            return back()->withErrors(['refund' => 'No refund transaction was found to check status.']);
        }

        try {
            $gateway = app(IciciPaymentGatewayService::class);
            $result = $gateway->checkRefundStatus($payment);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['refund' => $exception->getMessage()]);
        }

        $response = $result['response'] ?? [];
        $success = app(IciciPaymentGatewayService::class)->isRefundSuccess($response)
            || app(IciciPaymentGatewayService::class)->isPaymentSuccess($response);

        $payment->update([
            'refund_status_response' => $response,
            'refund_response_code' => (string) ($response['responseCode'] ?? $payment->refund_response_code),
            'refund_response_description' => (string) ($response['responseDescription'] ?? ($response['respdescription'] ?? ($response['error'] ?? $payment->refund_response_description))),
            'refund_status' => $success
                ? AdmissionPayment::REFUND_STATUS_SUCCESS
                : ($payment->refund_status ?: AdmissionPayment::REFUND_STATUS_PENDING),
            'refunded_at' => $success ? ($payment->refunded_at ?: now()) : $payment->refunded_at,
        ]);

        if ($success && $membershipUpload->status !== MembershipUpload::STATUS_REFUNDED) {
            $membershipUpload->update([
                'status' => MembershipUpload::STATUS_REFUNDED,
                'bns_status' => MembershipUpload::STEP_REFUNDED,
            ]);
        }

        $description = (string) ($response['responseDescription'] ?? ($response['respdescription'] ?? ($response['error'] ?? 'Status checked.')));

        return back()->with(
            'status',
            'Refund status: '.($response['responseCode'] ?? '—').' — '.$description
        );
    }

    public function sendPaymentRefundOtp(Request $request, AdmissionPayment $payment): JsonResponse|RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! Auth::user()?->isBnsVerifier()) {
            abort(403, 'Only the BNS login can process refunds.');
        }

        if (! $payment->isPaid()) {
            return $this->refundOtpResponse($request, false, 'Refund is available only for a successful payment.', 422);
        }

        if ($payment->isRefunded()) {
            return $this->refundOtpResponse($request, false, 'This payment has already been refunded. Use Check Refund Status.', 422);
        }

        $maxAmount = (float) $payment->amount;
        $validated = $request->validate([
            'refund_amount' => ['required', 'numeric', 'min:1', 'max:'.$maxAmount],
        ]);

        $refundAmount = number_format((float) $validated['refund_amount'], 2, '.', '');
        $otpLength = max(4, (int) config('reporting.refund_otp.length', 6));
        $ttlMinutes = max(1, (int) config('reporting.refund_otp.ttl_minutes', 10));
        $otpEmail = (string) config('reporting.refund_otp.email', 'mrupani2005@gmail.com');
        $otp = str_pad((string) random_int(0, (10 ** $otpLength) - 1), $otpLength, '0', STR_PAD_LEFT);
        $membership = $this->membershipForPayment($payment);

        Cache::put($this->refundOtpCacheKey($payment), [
            'hash' => Hash::make($otp),
            'amount' => $refundAmount,
            'attempts' => 0,
        ], now()->addMinutes($ttlMinutes));

        try {
            app(OutboundMailer::class)->send($otpEmail, new RefundOtpMail(
                $otp,
                (string) ($membership?->membership_name ?: $payment->customer_name ?: 'Participant'),
                (string) ($membership?->membership_no ?: ''),
                (string) ($payment->registration_number ?: ''),
                (string) ($membership?->mobile ?: $payment->customer_mobile ?: ''),
                $refundAmount,
                $ttlMinutes,
            ));
        } catch (Throwable $exception) {
            Cache::forget($this->refundOtpCacheKey($payment));
            report($exception);
            Log::error('Payment refund OTP email failed', [
                'email' => $otpEmail,
                'payment_id' => $payment->id,
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
                'message' => $exception->getMessage(),
            ]);

            return $this->refundOtpResponse(
                $request,
                false,
                'Unable to send OTP to '.$otpEmail.'. '.$exception->getMessage(),
                500
            );
        }

        return $this->refundOtpResponse(
            $request,
            true,
            'OTP sent to '.$otpEmail.'. Enter the OTP to complete the refund.'
        );
    }

    public function refundPayment(Request $request, AdmissionPayment $payment): RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! Auth::user()?->isBnsVerifier()) {
            abort(403, 'Only the BNS login can process refunds.');
        }

        if (! $payment->isPaid()) {
            return back()->withErrors(['refund' => 'Refund is available only for a successful payment.']);
        }

        if ($payment->isRefunded()) {
            return back()->withErrors(['refund' => 'This payment has already been refunded.']);
        }

        $maxAmount = (float) $payment->amount;
        $validated = $request->validate([
            'refund_amount' => ['required', 'numeric', 'min:1', 'max:'.$maxAmount],
            'otp' => ['required', 'digits:'.max(4, (int) config('reporting.refund_otp.length', 6))],
        ]);

        $refundAmount = number_format((float) $validated['refund_amount'], 2, '.', '');
        $otpPayload = Cache::get($this->refundOtpCacheKey($payment));

        if (! is_array($otpPayload) || empty($otpPayload['hash'])) {
            return back()->withErrors(['refund' => 'OTP expired or not sent. Please click Send OTP again.']);
        }

        $attempts = (int) ($otpPayload['attempts'] ?? 0);
        if ($attempts >= 5) {
            Cache::forget($this->refundOtpCacheKey($payment));

            return back()->withErrors(['refund' => 'Too many invalid OTP attempts. Please request a new OTP.']);
        }

        if (! Hash::check((string) $validated['otp'], (string) $otpPayload['hash'])) {
            $otpPayload['attempts'] = $attempts + 1;
            Cache::put(
                $this->refundOtpCacheKey($payment),
                $otpPayload,
                now()->addMinutes(max(1, (int) config('reporting.refund_otp.ttl_minutes', 10)))
            );

            return back()->withErrors(['refund' => 'Invalid OTP. Please check the email and try again.']);
        }

        if (($otpPayload['amount'] ?? null) !== $refundAmount) {
            return back()->withErrors(['refund' => 'Refund amount changed after OTP was sent. Please send a new OTP.']);
        }

        Cache::forget($this->refundOtpCacheKey($payment));

        $refundTxnNo = AdmissionPayment::generateRefundMerchantTxnNo();

        try {
            $gateway = app(IciciPaymentGatewayService::class);
            $result = $gateway->refund($payment, $refundAmount, $refundTxnNo);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['refund' => $exception->getMessage()]);
        }

        $response = $result['response'] ?? [];
        $success = app(IciciPaymentGatewayService::class)->isRefundSuccess($response);

        $payment->update([
            'refund_merchant_txn_no' => $refundTxnNo,
            'refund_amount' => $refundAmount,
            'refund_status' => $success
                ? AdmissionPayment::REFUND_STATUS_SUCCESS
                : AdmissionPayment::REFUND_STATUS_FAILED,
            'refund_response_code' => (string) ($response['responseCode'] ?? ''),
            'refund_response_description' => (string) ($response['responseDescription'] ?? ($response['respdescription'] ?? ($response['error'] ?? ''))),
            'refund_request' => $result['request'] ?? null,
            'refund_response' => $response,
            'refunded_at' => $success ? now() : null,
        ]);

        if ($success) {
            $membership = $this->membershipForPayment($payment);
            if ($membership && $membership->status !== MembershipUpload::STATUS_REFUNDED) {
                $membership->update([
                    'status' => MembershipUpload::STATUS_REFUNDED,
                    'bns_status' => MembershipUpload::STEP_REFUNDED,
                    'notes' => trim(($membership->notes ? $membership->notes."\n" : '').'Refund of ₹'.$refundAmount.' processed.'),
                ]);
            }

            return back()->with('status', 'Refund of ₹'.$refundAmount.' submitted successfully. Txn: '.$refundTxnNo);
        }

        $description = (string) ($response['responseDescription'] ?? ($response['respdescription'] ?? ($response['error'] ?? 'Refund request failed.')));

        return back()->withErrors(['refund' => 'Refund failed: '.$description]);
    }

    public function checkPaymentRefundStatus(Request $request, AdmissionPayment $payment): RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! Auth::user()?->isBnsVerifier()) {
            abort(403, 'Only the BNS login can check refund status.');
        }

        if (! $payment->refund_merchant_txn_no) {
            return back()->withErrors(['refund' => 'No refund transaction was found to check status.']);
        }

        try {
            $gateway = app(IciciPaymentGatewayService::class);
            $result = $gateway->checkRefundStatus($payment);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['refund' => $exception->getMessage()]);
        }

        $response = $result['response'] ?? [];
        $success = app(IciciPaymentGatewayService::class)->isRefundSuccess($response)
            || app(IciciPaymentGatewayService::class)->isPaymentSuccess($response);

        $payment->update([
            'refund_status_response' => $response,
            'refund_response_code' => (string) ($response['responseCode'] ?? $payment->refund_response_code),
            'refund_response_description' => (string) ($response['responseDescription'] ?? ($response['respdescription'] ?? ($response['error'] ?? $payment->refund_response_description))),
            'refund_status' => $success
                ? AdmissionPayment::REFUND_STATUS_SUCCESS
                : ($payment->refund_status ?: AdmissionPayment::REFUND_STATUS_PENDING),
            'refunded_at' => $success ? ($payment->refunded_at ?: now()) : $payment->refunded_at,
        ]);

        if ($success) {
            $membership = $this->membershipForPayment($payment);
            if ($membership && $membership->status !== MembershipUpload::STATUS_REFUNDED) {
                $membership->update([
                    'status' => MembershipUpload::STATUS_REFUNDED,
                    'bns_status' => MembershipUpload::STEP_REFUNDED,
                ]);
            }
        }

        $description = (string) ($response['responseDescription'] ?? ($response['respdescription'] ?? ($response['error'] ?? 'Status checked.')));

        return back()->with(
            'status',
            'Refund status: '.($response['responseCode'] ?? '—').' — '.$description
        );
    }

    public function attendance(Request $request): View|RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! bns_attendance_enabled()) {
            return redirect()->route('reporting.index');
        }

        $filters = $this->attendanceFiltersFromRequest($request);
        $rows = $this->filteredAttendances($filters);
        $session1Absent = $this->sessionAbsentRegistrations(1);

        return view('reporting.attendance', [
            'rows' => $rows,
            'search' => $filters['search'],
            'sessionFilter' => $filters['session'],
            'dateFrom' => $filters['date_from'],
            'dateTo' => $filters['date_to'],
            'session1Absent' => $session1Absent['rows'],
            'allowedSessions' => bns_intro_session_allowed_numbers(),
            'stats' => array_merge($this->attendanceSessionCounts(), [
                'total' => SessionAttendance::query()->count(),
                'today' => SessionAttendance::query()->whereDate('attended_at', today())->count(),
                'session_1_registered' => $session1Absent['registered'],
                'session_1_attended' => $session1Absent['attended'],
                'session_1_absent' => $session1Absent['absent'],
                'filtered' => $rows->count(),
            ]),
        ]);
    }

    public function exportAttendance(Request $request): View|RedirectResponse
    {
        $this->ensureReportingAccess();

        if (! bns_attendance_enabled()) {
            return redirect()->route('reporting.index');
        }
        $filters = $this->attendanceFiltersFromRequest($request);
        $rows = $this->filteredAttendances($filters);

        return view('reporting.attendance-pdf', [
            'rows' => $rows,
            'filters' => $filters,
            'generatedAt' => now(),
            'allowedSessions' => bns_intro_session_allowed_numbers(),
            'stats' => array_merge($this->attendanceSessionCounts($rows), [
                'total' => $rows->count(),
            ]),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $this->ensureReportingAccess();

        $filters = $this->filtersFromRequest($request);
        $allMatching = $this->filteredSubmissions($filters);
        $sessionMap = bns_reporting_session_number_map();
        $grouped = $this->groupSubmissionsBySession($allMatching, $sessionMap);
        $activeSession = $this->activeSessionFromRequest($request);

        $exportRows = $grouped[$activeSession] ?? collect();

        $filename = 'bns-reporting-session-'.$activeSession.'-'.now()->format('Y-m-d-His').'.xls';

        $headers = [
            'Session',
            'Date',
            'Time (IST)',
            'Full Name',
            'Mobile',
            'Email',
            'Submissions',
            'Registration No.',
            'Form Source',
            'Program',
            'Category',
            'City',
            'State',
            'Profession Category',
            'Business / Company',
            'Business Category',
            'Product / Service',
            'Message',
            'Status',
        ];

        return response()->streamDownload(function () use ($exportRows, $headers, $activeSession) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel opens Indian characters correctly.
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($exportRows as $item) {
                $label = 'Session '.$activeSession;

                fputcsv($handle, [
                    $label,
                    $item->created_at?->format('d M Y') ?? '',
                    $item->created_at?->format('h:i A') ?? '',
                    $item->full_name ?? '',
                    $item->mobile ?? '',
                    $item->email ?? '',
                    $item->submission_count ?? 1,
                    $item->registration_number ?? '',
                    $item->formSourceLabel(),
                    $item->interested_program ?? $item->subject ?? '',
                    $item->category ?? '',
                    $item->city ?? '',
                    $item->state ?? '',
                    $item->business_profession_category ?? '',
                    $item->organization_name ?? '',
                    $item->business_category ?? '',
                    $item->products_services ?? '',
                    $item->message ?? '',
                    $item->status ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    private function dashboard(Request $request): View|RedirectResponse
    {
        $today = now()->toDateString();
        $filters = $this->filtersFromRequest($request);
        $allMatching = $this->filteredSubmissions($filters);
        $submissions = $this->uniqueByMobile($allMatching);
        $totalMatching = $allMatching->count();
        $sessionMap = bns_reporting_session_number_map();
        $grouped = $this->groupSubmissionsBySession($allMatching, $sessionMap);
        $activeSession = $this->activeSessionFromRequest($request);
        $allowed = bns_intro_session_allowed_numbers();

        $countSource = static function ($rows, string $source): int {
            return $rows->filter(fn ($row) => $row->resolvedFormSource() === $source)->count();
        };

        $sessionEvents = [];
        $stats = [
            'unique_mobiles' => 0,
            'intro_session' => 0,
            'inquiry' => 0,
            'quick_register' => 0,
            'pending' => $submissions->filter(fn (ContactInquiry $row) => ($row->status ?? '') === 'pending')->count(),
            'contact_page' => ($grouped[0] ?? collect())
                ->filter(fn ($row) => $row->resolvedFormSource() === 'contact-page')
                ->count(),
            'filtered_total' => $totalMatching,
        ];

        foreach ($allowed as $sessionNo) {
            $rows = $grouped[$sessionNo] ?? collect();
            $intro = $countSource($rows, 'intro-session-modal');
            $inquiry = $countSource($rows, 'inquiry-modal');
            $confirm = $countSource($rows, 'register-quick-modal');
            $total = $rows->count();

            $todayCount = $rows
                ->filter(fn (ContactInquiry $row) => $row->created_at && $row->created_at->toDateString() === $today)
                ->count();

            $sessionEvents[$sessionNo] = bns_introduction_session($sessionNo);
            $stats['session_'.$sessionNo] = $total;
            $stats['session_'.$sessionNo.'_intro'] = $intro;
            $stats['session_'.$sessionNo.'_inquiry'] = $inquiry;
            $stats['session_'.$sessionNo.'_confirm'] = $confirm;
            $stats['session_'.$sessionNo.'_today'] = $todayCount;
            $stats['unique_mobiles'] += $total;
            $stats['intro_session'] += $intro;
            $stats['inquiry'] += $inquiry;
            $stats['quick_register'] += $confirm;
        }

        $stats['today'] = (int) ($stats['session_'.$activeSession.'_today'] ?? 0);

        $hasDateFilter = $filters['date_from'] !== '' || $filters['date_to'] !== '';
        $isTodayFilter = $filters['date_from'] === $today && $filters['date_to'] === $today;

        $session1Absent = $this->sessionAbsentRegistrations(1);
        $stats['session_1_registered'] = $session1Absent['registered'];
        $stats['session_1_attended'] = $session1Absent['attended'];
        $stats['session_1_absent'] = $session1Absent['absent'];

        return view('reporting.dashboard', [
            'submissions' => $submissions,
            'sessionGroups' => $grouped,
            'activeSession' => $activeSession,
            'sessionEvents' => $sessionEvents,
            'session1Absent' => $session1Absent['rows'],
            'totalMatching' => $totalMatching,
            'stats' => $stats,
            'search' => $filters['search'],
            'formSourceFilter' => $filters['form_source'],
            'categoryFilter' => $filters['category'],
            'programFilter' => $filters['program'],
            'dateFrom' => $filters['date_from'],
            'dateTo' => $filters['date_to'],
            'hasDateFilter' => $hasDateFilter,
            'isTodayDefault' => $isTodayFilter,
            'formSourceOptions' => config('reporting.form_sources', []),
            'categoryOptions' => config('contact.form_categories', []),
            'programOptions' => config('contact.form.interested_programs', []),
        ]);
    }

    private function activeSessionFromRequest(Request $request): int
    {
        $allowed = bns_intro_session_allowed_numbers();
        $default = (int) config('intro_session_form.default_session_number', $allowed[0] ?? 1);
        if (! in_array($default, $allowed, true)) {
            $default = $allowed[0] ?? 1;
        }

        $session = (int) $request->query('session', $default);

        return in_array($session, $allowed, true) ? $session : $default;
    }

    /**
     * Group submissions by introduction session, then unique by mobile within each session.
     *
     * @param  Collection<int, ContactInquiry>  $submissions
     * @param  array<string, int>  $sessionMap
     * @return array<int, Collection<int, ContactInquiry>>
     */
    private function groupSubmissionsBySession(Collection $submissions, array $sessionMap): array
    {
        $allowed = bns_intro_session_allowed_numbers();
        $groups = [0 => collect()];
        foreach ($allowed as $sessionNo) {
            $groups[$sessionNo] = collect();
        }

        $primarySources = [
            'intro-session-modal',
            'inquiry-modal',
            'register-quick-modal',
        ];

        foreach ($submissions as $item) {
            $source = $item->resolvedFormSource();
            if (! in_array($source, $primarySources, true)) {
                $groups[0]->push($item);
                continue;
            }

            $mobile = ContactInquiry::normalizeMobile($item->mobile);
            $key = $mobile !== '' ? $mobile : 'record-'.$item->id;
            $chosen = (int) ($item->intro_session_number ?? 0);
            $session = in_array($chosen, $allowed, true)
                ? $chosen
                : (int) ($sessionMap[$key] ?? 0);

            if (in_array($session, $allowed, true)) {
                $item->intro_session_number = $session;
                $groups[$session]->push($item);
            } else {
                $groups[0]->push($item);
            }
        }

        foreach ($groups as $sessionNo => $rows) {
            $groups[$sessionNo] = $this->uniqueByMobile($rows);
        }

        return $groups;
    }

    /** @return array{search: string, form_source: string, category: string, program: string, date_from: string, date_to: string} */
    private function filtersFromRequest(Request $request): array
    {
        return [
            'search' => trim((string) $request->query('q', '')),
            'form_source' => (string) $request->query('form_source', ''),
            'category' => (string) $request->query('category', ''),
            'program' => (string) $request->query('program', ''),
            'date_from' => (string) $request->query('date_from', ''),
            'date_to' => (string) $request->query('date_to', ''),
        ];
    }

    /**
     * @param  array{search: string, form_source: string, category: string, program: string, date_from: string, date_to: string}  $filters
     * @return array{0: Collection<int, ContactInquiry>, 1: int}
     */
    private function filteredUniqueSubmissions(array $filters): array
    {
        $allMatching = $this->filteredSubmissions($filters);

        return [$this->uniqueByMobile($allMatching), $allMatching->count()];
    }

    /**
     * @param  array{search: string, form_source: string, category: string, program: string, date_from: string, date_to: string}  $filters
     * @return Collection<int, ContactInquiry>
     */
    private function filteredSubmissions(array $filters): Collection
    {
        $query = ContactInquiry::query()->latest();

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('registration_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhere('organization_name', 'like', "%{$search}%")
                    ->orWhere('business_profession_category', 'like', "%{$search}%")
                    ->orWhere('business_category', 'like', "%{$search}%")
                    ->orWhere('products_services', 'like', "%{$search}%");
            });
        }

        if ($filters['form_source'] !== '') {
            if ($filters['form_source'] === 'unknown') {
                $query->where(function ($builder) {
                    $builder
                        ->whereNull('form_source')
                        ->orWhere('form_source', '')
                        ->orWhere('form_source', 'unknown');
                });
            } else {
                $query->where('form_source', $filters['form_source']);
            }
        }

        if ($filters['category'] !== '') {
            $query->where('category', $filters['category']);
        }

        if ($filters['program'] !== '') {
            $query->where('interested_program', $filters['program']);
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->get();
    }

    /**
     * @param  Collection<int, ContactInquiry>  $rows
     * @return Collection<int, ContactInquiry>
     */
    private function uniqueByMobile(Collection $rows): Collection
    {
        return $rows
            ->groupBy(function (ContactInquiry $item) {
                $mobile = ContactInquiry::normalizeMobile($item->mobile);

                return $mobile !== '' ? $mobile : 'record-'.$item->id;
            })
            ->map(function ($group) {
                $latest = $group->sortByDesc(fn (ContactInquiry $item) => $item->created_at)->first();
                $latest->submission_count = $group->count();

                return $latest;
            })
            ->sortByDesc(fn (ContactInquiry $item) => $item->created_at)
            ->values();
    }

    /**
     * @param  Collection<int, SessionAttendance>|null  $rows
     * @return array<string, int>
     */
    private function attendanceSessionCounts(?Collection $rows = null): array
    {
        $stats = [];
        foreach (bns_intro_session_allowed_numbers() as $sessionNo) {
            $stats['session_'.$sessionNo] = $rows
                ? $rows->where('session_number', $sessionNo)->count()
                : SessionAttendance::query()->where('session_number', $sessionNo)->count();
        }

        return $stats;
    }

    private function countUniqueMobiles($query): int
    {
        return $query->get()
            ->groupBy(function (ContactInquiry $item) {
                $mobile = ContactInquiry::normalizeMobile($item->mobile);

                return $mobile !== '' ? $mobile : 'record-'.$item->id;
            })
            ->count();
    }

    /** @return array{search: string, program: string, payment_mode: string, date_from: string, date_to: string, paid_date: string} */
    private function paymentFiltersFromRequest(Request $request): array
    {
        $paidDate = trim((string) $request->query('paid_date', ''));
        $dateFrom = trim((string) $request->query('date_from', ''));
        $dateTo = trim((string) $request->query('date_to', ''));

        if ($paidDate !== '') {
            $dateFrom = $paidDate;
            $dateTo = $paidDate;
        }

        return [
            'search' => trim((string) $request->query('q', '')),
            'program' => trim((string) $request->query('program', '')),
            'payment_mode' => trim((string) $request->query('payment_mode', '')),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'paid_date' => $paidDate,
        ];
    }

    /**
     * @param  list<string>  $registrationNumbers
     * @return array<string, MembershipUpload>
     */
    private function membershipsByRegistration(array $registrationNumbers): array
    {
        $registrationNumbers = array_values(array_filter(array_map(
            static fn ($value) => trim((string) $value),
            $registrationNumbers
        )));

        if ($registrationNumbers === []) {
            return [];
        }

        return MembershipUpload::query()
            ->whereIn('registration_number', $registrationNumbers)
            ->latest('id')
            ->get()
            ->unique(fn (MembershipUpload $upload) => trim((string) $upload->registration_number))
            ->keyBy(fn (MembershipUpload $upload) => trim((string) $upload->registration_number))
            ->all();
    }

    /**
     * @param  list<string>  $registrationNumbers
     * @return array<string, AdmissionPayment>
     */
    private function successfulPaymentsByRegistration(array $registrationNumbers): array
    {
        $registrationNumbers = array_values(array_filter(array_map(
            static fn ($value) => trim((string) $value),
            $registrationNumbers
        )));

        if ($registrationNumbers === []) {
            return [];
        }

        return AdmissionPayment::query()
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->whereIn('registration_number', $registrationNumbers)
            ->latest('paid_at')
            ->latest('id')
            ->get()
            ->unique(fn (AdmissionPayment $payment) => trim((string) $payment->registration_number))
            ->keyBy(fn (AdmissionPayment $payment) => trim((string) $payment->registration_number))
            ->all();
    }

    /**
     * @param  array{search: string, program: string, payment_mode: string, date_from: string, date_to: string}  $filters
     * @return Collection<int, AdmissionPayment>
     */
    private function filteredSuccessfulPayments(array $filters): Collection
    {
        $query = AdmissionPayment::query()
            ->with('payable')
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->latest('paid_at');

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('registration_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_mobile', 'like', "%{$search}%")
                    ->orWhere('merchant_txn_no', 'like', "%{$search}%")
                    ->orWhere('txn_id', 'like', "%{$search}%")
                    ->orWhere('payment_id', 'like', "%{$search}%");
            });
        }

        if ($filters['payment_mode'] !== '') {
            $query->where('payment_mode', $filters['payment_mode']);
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('paid_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('paid_at', '<=', $filters['date_to']);
        }

        $payments = $query->get()
            ->map(fn (AdmissionPayment $payment) => $this->decoratePayment($payment));

        if ($filters['program'] !== '') {
            $payments = $payments
                ->filter(fn (AdmissionPayment $payment) => $payment->display_program === $filters['program'])
                ->values();
        }

        return $payments;
    }

    private function decoratePayment(AdmissionPayment $payment): AdmissionPayment
    {
        $formLabel = (string) config(
            "payment.form_type_map.{$payment->form_type}.label",
            str($payment->form_type ?: 'Registration')->replace('-', ' ')->title()
        );
        $program = $formLabel;

        if ($payment->form_type === 'intro-session') {
            $storedProgram = trim((string) ($payment->payable->interested_program ?? ''));
            $program = (string) (config("pay_now.programs.{$storedProgram}") ?: $storedProgram ?: $formLabel);
        }

        $payment->setAttribute('display_program', $program);
        $payment->setAttribute('display_location', trim(implode(', ', array_filter([
            $payment->payable->city ?? null,
            $payment->payable->state ?? null,
        ]))));
        $payment->setAttribute('display_business', $payment->payable->organization_name ?? null);

        return $payment;
    }

    /** @return array{search: string, status: string, date_from: string, date_to: string} */
    private function membershipFiltersFromRequest(Request $request): array
    {
        return [
            'search' => trim((string) $request->query('q', '')),
            'status' => trim((string) $request->query('status', '')),
            'date_from' => trim((string) $request->query('date_from', '')),
            'date_to' => trim((string) $request->query('date_to', '')),
        ];
    }

    /**
     * @param  array{search: string, status: string, date_from: string, date_to: string}  $filters
     * @return Collection<int, MembershipUpload>
     */
    private function filteredMembershipUploads(array $filters): Collection
    {
        $query = MembershipUpload::query()
            ->with(['trusteeVerifier', 'bnsVerifier'])
            ->latest();

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('membership_name', 'like', "%{$search}%")
                    ->orWhere('membership_no', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        if ($filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->get();
    }

    /** @return array{search: string, session: string, date_from: string, date_to: string} */
    private function attendanceFiltersFromRequest(Request $request): array
    {
        return [
            'search' => trim((string) $request->query('q', '')),
            'session' => trim((string) $request->query('session', '')),
            'date_from' => trim((string) $request->query('date_from', '')),
            'date_to' => trim((string) $request->query('date_to', '')),
        ];
    }

    /**
     * @param  array{search: string, session: string, date_from: string, date_to: string}  $filters
     * @return Collection<int, SessionAttendance>
     */
    private function filteredAttendances(array $filters): Collection
    {
        $query = SessionAttendance::query()
            ->with('inquiry')
            ->latest('attended_at');

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('registration_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%");
            });
        }

        if ($filters['session'] !== '' && in_array((int) $filters['session'], bns_intro_session_allowed_numbers(), true)) {
            $query->where('session_number', (int) $filters['session']);
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('attended_at', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('attended_at', '<=', $filters['date_to']);
        }

        return $query->get();
    }

    /**
     * Registered unique mobiles for a session who have not marked attendance.
     *
     * @return array{rows: Collection<int, ContactInquiry>, registered: int, attended: int, absent: int}
     */
    private function sessionAbsentRegistrations(int $sessionNumber = 1): array
    {
        $breakdown = bns_session_attendance_breakdown($sessionNumber);

        return [
            'rows' => $breakdown['absent_rows'],
            'registered' => $breakdown['registered'],
            'attended' => $breakdown['present'],
            'absent' => $breakdown['absent'],
        ];
    }

    /** @deprecated Use sessionAbsentRegistrations() */
    private function session1AbsentRegistrations(): array
    {
        return $this->sessionAbsentRegistrations(1);
    }

    private function membershipForPayment(AdmissionPayment $payment): ?MembershipUpload
    {
        $registrationNumber = trim((string) $payment->registration_number);
        if ($registrationNumber === '') {
            return null;
        }

        return MembershipUpload::query()
            ->where('registration_number', $registrationNumber)
            ->latest('id')
            ->first();
    }

    private function refundOtpCacheKey(AdmissionPayment $payment): string
    {
        return 'reporting.refund_otp.payment.'.$payment->id.'.'.(Auth::id() ?: 'guest');
    }

    private function refundOtpResponse(
        Request $request,
        bool $success,
        string $message,
        int $errorStatus = 422
    ): JsonResponse|RedirectResponse {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
            ], $success ? 200 : $errorStatus);
        }

        if ($success) {
            return back()->with('status', $message);
        }

        return back()->withErrors(['refund' => $message]);
    }

    private function ensureReportingAccess(): void
    {
        if (! Auth::user()?->isSopAdmin()) {
            abort(403, 'Administrator access required.');
        }
    }

    private function storedPassword(User $user): string
    {
        return (string) ($user->getAttributes()['password'] ?? '');
    }

    private function isBcryptHash(string $hash): bool
    {
        return str_starts_with($hash, '$2y$')
            || str_starts_with($hash, '$2a$')
            || str_starts_with($hash, '$2b$');
    }
}
