<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\SessionAttendance;
use App\Services\AttendanceConfirmationMailer;
use App\Services\IntroSessionConfirmationMailer;
use App\Services\RegistrationPaymentService;
use App\Services\TestRegistrationPurgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private RegistrationPaymentService $payments,
        private AttendanceConfirmationMailer $mailer,
        private IntroSessionConfirmationMailer $introSessionMailer,
        private TestRegistrationPurgeService $testPurge,
    ) {}

    public function index(): View
    {
        abort_unless(bns_attendance_enabled(), 404);

        return view('attendance.index', [
            'programs' => config('pay_now.programs', []),
            'stickyIntro' => config('home.sticky_cta.intro_session', []),
        ]);
    }

    public function lookup(Request $request): JsonResponse
    {
        abort_unless(bns_attendance_enabled(), 404);

        $validated = $request->validate([
            'lookup_type' => ['required', 'in:reference,email,mobile'],
            'reference_last4' => ['required_if:lookup_type,reference', 'nullable', 'digits:4'],
            'email' => ['required_if:lookup_type,email', 'nullable', 'email', 'max:255'],
            'mobile' => ['required_if:lookup_type,mobile', 'nullable', 'string', 'max:30'],
        ]);

        $query = ContactInquiry::primaryFormsQuery()->orderByDesc('id');

        if ($validated['lookup_type'] === 'reference') {
            $query->where('registration_number', 'like', '%'.$validated['reference_last4']);
        } elseif ($validated['lookup_type'] === 'mobile') {
            $digits = preg_replace('/\D+/', '', (string) $validated['mobile']);
            $last10 = substr((string) $digits, -10);
            $query->where(function ($q) use ($digits, $last10) {
                $q->where('mobile', 'like', '%'.$last10)
                    ->orWhere('mobile', 'like', '%'.$digits)
                    ->orWhere('whatsapp', 'like', '%'.$last10);
            });
        } else {
            $query->whereRaw('LOWER(email) = ?', [strtolower($validated['email'])]);
        }

        $matches = $query->limit(10)->get();

        if ($matches->isEmpty()) {
            return response()->json([
                'ok' => false,
                'not_registered' => true,
                'message' => 'No session booking found. Please check your Reference Number / registered email / mobile number.',
            ], 404);
        }

        $programs = config('pay_now.programs', []);

        $records = $matches->map(function (ContactInquiry $inquiry) use ($programs) {
            $sessionNumber = bns_intro_session_number_for_mobile($inquiry->mobile) ?: 1;
            $event = bns_introduction_session((int) $sessionNumber) ?? [];
            $attendance = SessionAttendance::query()
                ->where('contact_inquiry_id', $inquiry->id)
                ->where('session_number', $sessionNumber)
                ->first();
            $paid = (bool) $this->payments->latestSuccessfulForRegistration((string) $inquiry->registration_number);
            $programKey = (string) ($inquiry->interested_program ?? '');
            $programLabel = $programs[$programKey] ?? ($programKey !== '' ? $programKey : '—');

            return [
                'id' => $inquiry->id,
                'registration_number' => $inquiry->registration_number,
                'full_name' => $inquiry->full_name,
                'email' => $inquiry->email,
                'mobile' => $inquiry->mobile,
                'program' => $programLabel,
                'session_number' => (int) $sessionNumber,
                'session_label' => 'Session '.(int) $sessionNumber,
                'session_date' => $event['date'] ?? null,
                'session_time' => $event['time'] ?? ($event['time_label'] ?? null),
                'already_paid' => $paid,
                'already_attended' => (bool) $attendance,
                'attended_at' => $attendance?->attended_at
                    ? \Illuminate\Support\Carbon::parse($attendance->attended_at)->format('d M Y, h:i A')
                    : null,
            ];
        })->values();

        return response()->json([
            'ok' => true,
            'records' => $records,
        ]);
    }

    public function mark(Request $request): JsonResponse
    {
        abort_unless(bns_attendance_enabled(), 404);

        $validated = $request->validate([
            'inquiry_id' => ['required', 'integer', 'exists:contact_inquiries,id'],
        ]);

        $inquiry = ContactInquiry::primaryFormsQuery()
            ->where('id', $validated['inquiry_id'])
            ->firstOrFail();

        return $this->markAttendanceForInquiry($inquiry, $request);
    }

    /**
     * Walk-in: create Introduction Session registration + mark attendance together.
     */
    public function registerAndMark(Request $request): JsonResponse
    {
        abort_unless(bns_attendance_enabled(), 404);

        $form = config('contact.form');
        $categories = config('contact.form_categories');
        $programs = config('register.quick_modal_programs', []);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => [
                'required',
                'string',
                'max:30',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->testPurge->isAutoPurgeMobile((string) $value)) {
                        return;
                    }
                    if (ContactInquiry::mobileExists((string) $value)) {
                        $fail('This mobile number is already registered. Please use Attendance Confirm with your mobile number.');
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if ($this->testPurge->isAutoPurgeMobile((string) $request->input('mobile'))) {
                        return;
                    }
                    if (ContactInquiry::emailExists((string) $value)) {
                        $fail('This email is already registered. Please use Attendance Confirm with your registered email.');
                    }
                },
            ],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'interested_program' => ['required', 'string', Rule::in($form['interested_programs'] ?? [])],
            'category' => ['required', 'string', Rule::in($categories ?? [])],
            'register_program_choice' => ['nullable', 'string', 'max:100'],
        ]);

        $programTitle = $validated['interested_program'];
        foreach ($programs as $program) {
            if (($program['id'] ?? '') === ($request->input('register_program_choice') ?? '')) {
                $programTitle = (string) ($program['title'] ?? $programTitle);
                break;
            }
        }

        $introCountBefore = bns_intro_session_unique_mobile_count();
        $sessionNumber = bns_intro_session_number_for_count($introCountBefore);
        $registrationNumber = ContactInquiry::generateRegistrationNumber();

        $inquiry = ContactInquiry::query()->create([
            'registration_number' => $registrationNumber,
            'form_source' => 'intro-session-modal',
            'full_name' => $validated['full_name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => 'India',
            'interested_program' => $validated['interested_program'],
            'category' => $validated['category'],
            'intro_session_number' => $sessionNumber,
            'subject' => $validated['interested_program'],
            'message' => 'Introduction session admission request via Attendance walk-in ('.$programTitle.').',
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
            'interested_program' => $validated['interested_program'],
            'city' => $validated['city'],
            'state' => $validated['state'],
        ], $sessionNumber);

        $markResult = $this->markAttendanceForInquiry($inquiry, $request, $sessionNumber);

        $payload = $markResult->getData(true);
        $payload['registration_number'] = $registrationNumber;
        $payload['message'] = 'Registration and attendance confirmed successfully. Confirmation email has been sent.';
        $payload['registered'] = true;

        return response()->json($payload, $markResult->getStatusCode());
    }

    private function markAttendanceForInquiry(
        ContactInquiry $inquiry,
        Request $request,
        ?int $sessionNumber = null
    ): JsonResponse {
        $sessionNumber ??= bns_intro_session_number_for_mobile($inquiry->mobile) ?: 1;

        $existing = SessionAttendance::query()
            ->where('contact_inquiry_id', $inquiry->id)
            ->where('session_number', $sessionNumber)
            ->first();

        if ($existing) {
            return response()->json([
                'ok' => false,
                'message' => 'Attendance already marked for this session.',
                'already_attended' => true,
                'attended_at' => $existing->attended_at
                    ? \Illuminate\Support\Carbon::parse($existing->attended_at)->format('d M Y, h:i A')
                    : null,
            ], 422);
        }

        $programs = config('pay_now.programs', []);
        $programKey = (string) ($inquiry->interested_program ?? '');
        $programLabel = $programs[$programKey] ?? ($programKey !== '' ? $programKey : null);

        $attendance = SessionAttendance::query()->create([
            'contact_inquiry_id' => $inquiry->id,
            'registration_number' => $inquiry->registration_number,
            'session_number' => $sessionNumber,
            'full_name' => $inquiry->full_name,
            'email' => $inquiry->email,
            'mobile' => $inquiry->mobile,
            'program' => $programLabel,
            'status' => SessionAttendance::STATUS_PRESENT,
            'marked_via' => 'self',
            'attended_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        $this->mailer->send($attendance);

        return response()->json([
            'ok' => true,
            'message' => 'Attendance marked successfully. A confirmation email has been sent.',
            'already_attended' => true,
            'attended_at' => $attendance->attended_at?->format('d M Y, h:i A'),
            'session_label' => $attendance->sessionLabel(),
            'registration_number' => $inquiry->registration_number,
        ]);
    }
}
