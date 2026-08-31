<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\MembershipUpload;
use App\Services\IntroSessionConfirmationMailer;
use App\Services\RegistrationPaymentService;
use App\Services\TestRegistrationPurgeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;

class PayNowController extends Controller
{
    public function __construct(
        private RegistrationPaymentService $payments,
        private IntroSessionConfirmationMailer $introSessionMailer,
        private TestRegistrationPurgeService $testPurge,
    ) {}

    public function index(): View
    {
        $errors = session('errors');
        $hasValidationErrors = $errors instanceof ViewErrorBag && $errors->any();
        $openPayNowLookup = $hasValidationErrors && old('pay_now_submit');

        return view('pay-now.index', [
            'banner' => config('pay_now.banner'),
            'scholarshipAmount' => config('pay_now.scholarship_amount', 3160),
            'paymentAmount' => number_format((float) config('pay_now.payment_amount', config('payment.default_amount', '11800.00')), 2, '.', ''),
            'programs' => config('pay_now.form_programs', config('pay_now.programs', [])),
            'openPayNowLookup' => (bool) $openPayNowLookup || (bool) old('pay_now_submit'),
            'stickyIntro' => config('home.sticky_cta.intro_session', []),
        ]);
    }

    public function uploadMembership(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'membership_name' => ['required', 'string', 'max:255'],
            'membership_no' => ['required', 'string', 'max:100'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'registration_number' => ['nullable', 'string', 'max:40'],
            'merchant_txn_no' => ['nullable', 'string', 'max:40'],
        ]);

        $existing = MembershipUpload::findExistingActive(
            $validated['registration_number'] ?? null,
            $validated['membership_no'] ?? null,
        );

        $merchantTxnNo = (string) ($validated['merchant_txn_no'] ?? '');
        $redirect = $merchantTxnNo !== ''
            ? redirect()->route('payment.success', $merchantTxnNo)
            : redirect()->route('pay-now');

        if ($existing) {
            return $redirect
                ->withInput($request->except('photo'))
                ->withErrors([
                    'membership_no' => 'Membership proof already uploaded for this registration / membership number. Please wait for verification.',
                ]);
        }

        $path = MembershipUpload::storePhoto($request->file('photo'));

        MembershipUpload::query()->create([
            'membership_name' => $validated['membership_name'],
            'membership_no' => $validated['membership_no'],
            'photo_path' => $path,
            'email' => $validated['email'] ?? null,
            'mobile' => $validated['mobile'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'status' => 'pending',
        ]);

        $message = 'Membership proof uploaded successfully. We will verify and process your ₹'.number_format((int) config('pay_now.scholarship_amount', 3160)).' scholarship refund.';

        return $redirect->with('success', $message);
    }

    /**
     * Name + Email + Mobile → match by mobile (existing) or create new → go to payment.
     */
    public function submit(Request $request): RedirectResponse
    {
        $programKeys = array_keys(config('pay_now.form_programs', config('pay_now.programs', [])));

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:30'],
            'interested_program' => ['required', 'string', 'in:'.implode(',', $programKeys)],
            'gst_no' => ['nullable', 'string', 'max:20', 'regex:/^[0-9A-Z]{15}$/i'],
        ], [
            'gst_no.regex' => 'Please enter a valid 15-character GSTIN.',
        ], [
            'full_name' => 'name',
            'mobile' => 'mobile number',
            'interested_program' => 'program',
            'gst_no' => 'GST number',
        ]);

        $validated['gst_no'] = isset($validated['gst_no']) && $validated['gst_no'] !== ''
            ? strtoupper(trim($validated['gst_no']))
            : null;

        $normalizedMobile = ContactInquiry::normalizeMobile($validated['mobile']);
        if ($normalizedMobile === '' || strlen($normalizedMobile) < 10) {
            return redirect()
                ->route('pay-now')
                ->withInput($request->all() + ['pay_now_submit' => 1])
                ->withErrors(['mobile' => 'Please enter a valid 10-digit mobile number.']);
        }

        $inquiry = $this->findByMobile($normalizedMobile);

        if ($inquiry) {
            $inquiry->update([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'interested_program' => $validated['interested_program'],
                'subject' => $validated['interested_program'],
                'gst_no' => $validated['gst_no'],
            ]);
            $inquiry->refresh();
            $isNew = false;
        } else {
            $inquiry = $this->createNewRegistration($validated);
            $isNew = true;
        }

        if ($this->payments->latestSuccessfulForRegistration((string) $inquiry->registration_number)) {
            return redirect()
                ->route('pay-now')
                ->with('info', 'Payment already completed for '.$inquiry->registration_number.'.');
        }

        $checkout = $this->payments->redirectToCheckoutForIntroSession($inquiry);

        if ($isNew) {
            return $checkout->with(
                'info',
                'New registration created ('.$inquiry->registration_number.'). Please complete payment.'
            );
        }

        return $checkout->with(
            'info',
            'Existing booking found ('.$inquiry->registration_number.'). Please complete payment.'
        );
    }

    public function pay(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'inquiry_id' => ['required', 'integer', 'exists:contact_inquiries,id'],
        ]);

        $inquiry = ContactInquiry::primaryFormsQuery()
            ->where('id', $validated['inquiry_id'])
            ->firstOrFail();

        if ($this->payments->latestSuccessfulForRegistration((string) $inquiry->registration_number)) {
            return redirect()
                ->route('pay-now')
                ->with('info', 'Payment already completed for '.$inquiry->registration_number.'.');
        }

        return $this->payments->redirectToCheckoutForIntroSession($inquiry);
    }

    private function findByMobile(string $normalizedMobile): ?ContactInquiry
    {
        $last10 = substr($normalizedMobile, -10);

        return ContactInquiry::primaryFormsQuery()
            ->where(function ($query) use ($last10, $normalizedMobile) {
                $query
                    ->where('mobile', 'like', '%'.$last10)
                    ->orWhere('mobile', 'like', '%'.$normalizedMobile)
                    ->orWhere('whatsapp', 'like', '%'.$last10);
            })
            ->orderByDesc('id')
            ->get()
            ->first(function (ContactInquiry $item) use ($normalizedMobile) {
                return ContactInquiry::normalizeMobile($item->mobile) === $normalizedMobile
                    || ContactInquiry::normalizeMobile($item->whatsapp) === $normalizedMobile;
            });
    }

    /**
     * @param  array{full_name: string, email: string, mobile: string, interested_program: string, gst_no?: string|null}  $validated
     */
    private function createNewRegistration(array $validated): ContactInquiry
    {
        $intro = config('home.sticky_cta.intro_session', []);
        $program = $validated['interested_program'];
        $category = (string) ($intro['contact_category'] ?? 'Other');
        $introCountBefore = bns_intro_session_unique_mobile_count();
        $sessionNumber = bns_intro_session_number_for_count($introCountBefore);
        $registrationNumber = ContactInquiry::generateRegistrationNumber();

        $inquiry = ContactInquiry::query()->create([
            'registration_number' => $registrationNumber,
            'form_source' => 'intro-session-modal',
            'full_name' => $validated['full_name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'gst_no' => $validated['gst_no'] ?? null,
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
            'interested_program' => $program,
            'category' => $category,
            'intro_session_number' => $sessionNumber,
            'subject' => $program,
            'message' => 'Introduction session admission request via Pay Now (auto mobile match).',
            'agreed_to_contact' => true,
            'agreed_info_correct' => true,
            'agreed_privacy' => true,
            'status' => 'pending',
        ]);

        $this->testPurge->scheduleIfNeeded($inquiry);

        $this->introSessionMailer->send([
            'registration_number' => $registrationNumber,
            'full_name' => $validated['full_name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'interested_program' => $program,
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
        ], $sessionNumber);

        return $inquiry;
    }
}
