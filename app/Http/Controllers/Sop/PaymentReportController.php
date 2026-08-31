<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPayment;
use App\Models\ContactInquiry;
use App\Models\IntroSessionEmailLog;
use App\Services\IntroSessionConfirmationMailer;
use App\Services\RegistrationPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentReportController extends Controller
{
    public function __construct(
        private RegistrationPaymentService $payments,
        private IntroSessionConfirmationMailer $mailer,
    ) {}

    public function index(Request $request): View
    {
        $filters = $this->filtersFromRequest($request);
        $query = $this->filteredPaymentsQuery($filters)->with('payable')->latest();
        $payments = $query->paginate(30)->withQueryString();

        $stats = [
            'total' => AdmissionPayment::query()->count(),
            'success' => AdmissionPayment::query()->where('status', AdmissionPayment::STATUS_SUCCESS)->count(),
            'pending' => AdmissionPayment::query()->whereIn('status', [
                AdmissionPayment::STATUS_PENDING,
                AdmissionPayment::STATUS_INITIATED,
            ])->count(),
            'failed' => AdmissionPayment::query()->where('status', AdmissionPayment::STATUS_FAILED)->count(),
            'amount_collected' => AdmissionPayment::query()
                ->where('status', AdmissionPayment::STATUS_SUCCESS)
                ->sum('amount'),
            'filtered' => (clone $this->filteredPaymentsQuery($filters))->count(),
            'with_email' => (clone $this->filteredPaymentsQuery($filters))
                ->whereNotNull('customer_email')
                ->where('customer_email', '!=', '')
                ->count(),
        ];

        $emailTemplates = bns_message_email_templates();
        $selectedTemplate = (string) $request->query('template', IntroSessionConfirmationMailer::TEMPLATE_WELCOME);
        $sessions = bns_introduction_sessions();
        $sessionForPreview = $filters['session'] ?: (bns_intro_session_allowed_numbers()[0] ?? 1);
        $event = bns_introduction_session((int) $sessionForPreview) ?? [];

        $templatePreviews = [];
        foreach ($emailTemplates as $template) {
            $id = (string) ($template['id'] ?? '');
            if ($id === '') {
                continue;
            }
            $templatePreviews[$id] = [
                'id' => $id,
                'stage' => (string) ($template['stage'] ?? ''),
                'title' => (string) ($template['title'] ?? ''),
                'preview_html' => '',
            ];
        }

        if ($selectedTemplate === IntroSessionConfirmationMailer::TEMPLATE_WELCOME) {
            $templatePreviews[$selectedTemplate] = [
                'id' => $selectedTemplate,
                'stage' => 'System',
                'title' => 'Welcome Confirmation + Calendar',
                'preview_html' => view('emails.partials.session-venue-card', [
                    'venueCard' => bns_intro_session_venue_card($event),
                ])->render(),
            ];
        } else {
            $full = bns_message_email_template($selectedTemplate);
            if ($full !== null) {
                $templatePreviews[$selectedTemplate] = [
                    'id' => $selectedTemplate,
                    'stage' => (string) ($full['stage'] ?? ''),
                    'title' => (string) ($full['title'] ?? ''),
                    'preview_html' => (string) ($full['rich_html'] ?? $full['body_html'] ?? ''),
                ];
            }
        }

        $logs = IntroSessionEmailLog::query()
            ->with('sender')
            ->where('batch_key', 'like', 'pay-%')
            ->orderByDesc('sent_at')
            ->orderByDesc('id')
            ->paginate(30, ['*'], 'log_page')
            ->withQueryString();

        return view('sop.payments.index', [
            'payments' => $payments,
            'stats' => $stats,
            'search' => $filters['search'],
            'statusFilter' => $filters['status'],
            'formTypeFilter' => $filters['form_type'],
            'sessionFilter' => $filters['session'],
            'statusOptions' => $this->statusOptions(),
            'formTypeOptions' => config('payment.form_type_map', []),
            'sessions' => $sessions,
            'allowedSessions' => bns_intro_session_allowed_numbers(),
            'emailTemplates' => $emailTemplates,
            'selectedTemplate' => $selectedTemplate,
            'templatePreviews' => $templatePreviews,
            'previewUrl' => route('controlpanel.payments.email-preview'),
            'logs' => $logs,
        ]);
    }

    public function emailPreview(Request $request): JsonResponse
    {
        $templateId = trim((string) $request->query('template', IntroSessionConfirmationMailer::TEMPLATE_WELCOME));
        $session = (int) $request->query('session', bns_intro_session_allowed_numbers()[0] ?? 1);
        $event = bns_introduction_session($session) ?? [];

        if ($templateId === '' || $templateId === IntroSessionConfirmationMailer::TEMPLATE_WELCOME) {
            return response()->json([
                'id' => IntroSessionConfirmationMailer::TEMPLATE_WELCOME,
                'stage' => 'System',
                'title' => 'Welcome Confirmation + Calendar',
                'preview_html' => view('emails.partials.session-venue-card', [
                    'venueCard' => bns_intro_session_venue_card($event),
                ])->render(),
            ]);
        }

        $full = bns_message_email_template($templateId);
        if ($full === null) {
            return response()->json(['error' => 'Template not found.'], 404);
        }

        return response()->json([
            'id' => $templateId,
            'stage' => (string) ($full['stage'] ?? ''),
            'title' => (string) ($full['title'] ?? ''),
            'preview_html' => (string) ($full['rich_html'] ?? $full['body_html'] ?? ''),
        ]);
    }

    public function sendMail(Request $request): RedirectResponse
    {
        $allowedSessions = bns_intro_session_allowed_numbers();
        $templateIds = collect(bns_message_email_templates())->pluck('id')->all();

        $validated = $request->validate([
            'template' => ['required', 'string', 'in:'.implode(',', $templateIds)],
            'session' => ['nullable', 'integer', 'in:'.implode(',', $allowedSessions)],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:admission_payments,id'],
            'q' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'form_type' => ['nullable', 'string'],
        ], [
            'template.required' => 'Please select an email template.',
            'ids.required' => 'Please select at least one payment row.',
            'ids.min' => 'Please select at least one payment row.',
        ]);

        $templateId = (string) $validated['template'];
        $templateMeta = bns_message_email_template($templateId);
        $templateTitle = (string) ($templateMeta['title'] ?? $templateId);
        $defaultSession = (int) ($validated['session'] ?? ($allowedSessions[0] ?? 1));
        if (! in_array($defaultSession, $allowedSessions, true)) {
            $defaultSession = $allowedSessions[0] ?? 1;
        }

        $payments = AdmissionPayment::query()
            ->with('payable')
            ->whereIn('id', $validated['ids'])
            ->get();

        $sent = 0;
        $skipped = 0;
        $failed = 0;
        $batchKey = 'pay-'.Str::lower(Str::random(14));
        $sentBy = $request->user()?->id;
        $now = now();

        foreach ($payments as $payment) {
            $email = trim((string) ($payment->customer_email ?? ''));
            $sessionNumber = $this->sessionNumberForPayment($payment) ?: $defaultSession;
            $inquiryId = null;
            if ($payment->payable instanceof ContactInquiry) {
                $inquiryId = $payment->payable->id;
            }

            $baseLog = [
                'contact_inquiry_id' => $inquiryId,
                'session_number' => $sessionNumber,
                'template_key' => $templateId,
                'template_title' => $templateTitle,
                'registration_number' => $payment->registration_number,
                'full_name' => $payment->customer_name,
                'email' => $email !== '' ? $email : null,
                'mobile' => $payment->customer_mobile,
                'sent_by' => $sentBy,
                'batch_key' => $batchKey,
                'sent_at' => $now,
            ];

            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                IntroSessionEmailLog::query()->create([
                    ...$baseLog,
                    'status' => IntroSessionEmailLog::STATUS_SKIPPED,
                    'error_message' => 'Missing or invalid email address.',
                ]);
                $skipped++;
                continue;
            }

            $result = $this->mailer->send([
                'registration_number' => $payment->registration_number,
                'full_name' => $payment->customer_name,
                'email' => $email,
                'mobile' => $payment->customer_mobile,
                'interested_program' => config("payment.form_type_map.{$payment->form_type}.label", $payment->form_type),
            ], $sessionNumber, $templateId);

            if ($result['ok']) {
                IntroSessionEmailLog::query()->create([
                    ...$baseLog,
                    'template_key' => $result['template_key'] ?: $templateId,
                    'template_title' => $result['template_title'] ?: $templateTitle,
                    'status' => IntroSessionEmailLog::STATUS_SENT,
                    'error_message' => null,
                ]);
                $sent++;
            } else {
                IntroSessionEmailLog::query()->create([
                    ...$baseLog,
                    'template_key' => $result['template_key'] ?: $templateId,
                    'template_title' => $result['template_title'] ?: $templateTitle,
                    'status' => IntroSessionEmailLog::STATUS_FAILED,
                    'error_message' => $result['error'] ?? 'Email send failed.',
                ]);
                $failed++;
            }
        }

        return redirect()
            ->route('controlpanel.payments.index', array_filter([
                'q' => $request->input('q'),
                'status' => $request->input('status'),
                'form_type' => $request->input('form_type'),
                'session' => $request->input('session'),
                'template' => $templateId,
            ], fn ($v) => $v !== null && $v !== ''))
            ->with('status', "Template \"{$templateTitle}\": Sent {$sent}, Skipped {$skipped}, Failed {$failed}. Log saved.");
    }

    public function show(AdmissionPayment $payment): View
    {
        $payment->load('payable');

        return view('sop.payments.show', [
            'payment' => $payment,
            'formLabel' => config("payment.form_type_map.{$payment->form_type}.label", 'Registration'),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function refreshStatus(AdmissionPayment $payment): RedirectResponse
    {
        $this->payments->processCallback($payment, $payment->callback_response ?? []);

        return back()->with('status', 'Payment status refreshed from gateway.');
    }

    /**
     * @return array{search: string, status: string, form_type: string, session: int|string}
     */
    private function filtersFromRequest(Request $request): array
    {
        $formType = trim((string) $request->query('form_type', ''));
        $allowedTypes = array_keys(config('payment.form_type_map', []));
        if ($formType !== '' && ! in_array($formType, $allowedTypes, true)) {
            $formType = '';
        }

        $session = trim((string) $request->query('session', ''));
        $allowedSessions = bns_intro_session_allowed_numbers();
        if ($session !== '' && ! in_array((int) $session, $allowedSessions, true)) {
            $session = '';
        }

        $status = trim((string) $request->query('status', ''));
        if ($status !== '' && ! in_array($status, $this->statusOptions(), true)) {
            $status = '';
        }

        return [
            'search' => trim((string) $request->query('q', '')),
            'status' => $status,
            'form_type' => $formType,
            'session' => $session !== '' ? (int) $session : '',
        ];
    }

    /**
     * @param  array{search: string, status: string, form_type: string, session: int|string}  $filters
     */
    private function filteredPaymentsQuery(array $filters)
    {
        $query = AdmissionPayment::query();

        if ($filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if ($filters['form_type'] !== '') {
            $query->where('form_type', $filters['form_type']);
        }

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('registration_number', 'like', "%{$search}%")
                    ->orWhere('merchant_txn_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_mobile', 'like', "%{$search}%")
                    ->orWhere('payment_id', 'like', "%{$search}%")
                    ->orWhere('txn_id', 'like', "%{$search}%");
            });
        }

        if ($filters['session'] !== '') {
            $session = (int) $filters['session'];
            $inquiryIds = $this->inquiryIdsForSession($session);

            $query->where(function ($builder) use ($session, $inquiryIds) {
                $builder->where(function ($inner) use ($session) {
                    $inner->where('form_type', 'intro-session')
                        ->whereHasMorph('payable', [ContactInquiry::class], function ($payable) use ($session) {
                            $payable->where('intro_session_number', $session);
                        });
                });

                if ($inquiryIds !== []) {
                    $builder->orWhere(function ($inner) use ($inquiryIds) {
                        $inner->where('payable_type', (new ContactInquiry)->getMorphClass())
                            ->whereIn('payable_id', $inquiryIds);
                    });
                }
            });
        }

        return $query;
    }

    /** @return list<int> */
    private function inquiryIdsForSession(int $session): array
    {
        $map = bns_reporting_session_mobile_map(onlyIntro: false);

        return collect($map)
            ->filter(fn (array $row) => (int) ($row['session'] ?? 0) === $session)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function sessionNumberForPayment(AdmissionPayment $payment): int
    {
        $payable = $payment->payable;
        if ($payable instanceof ContactInquiry) {
            $stored = (int) ($payable->intro_session_number ?? 0);
            if (in_array($stored, bns_intro_session_allowed_numbers(), true)) {
                return $stored;
            }

            $mobile = ContactInquiry::normalizeMobile($payable->mobile ?: $payment->customer_mobile);
            if ($mobile !== '') {
                $map = bns_reporting_session_mobile_map(onlyIntro: false);
                foreach ($map as $key => $row) {
                    if ((string) $key === $mobile || ContactInquiry::normalizeMobile((string) ($row['mobile'] ?? '')) === $mobile) {
                        $session = (int) ($row['session'] ?? 0);
                        if (in_array($session, bns_intro_session_allowed_numbers(), true)) {
                            return $session;
                        }
                    }
                }
            }
        }

        return bns_intro_session_allowed_numbers()[0] ?? 1;
    }

    /** @return list<string> */
    private function statusOptions(): array
    {
        return [
            AdmissionPayment::STATUS_PENDING,
            AdmissionPayment::STATUS_INITIATED,
            AdmissionPayment::STATUS_SUCCESS,
            AdmissionPayment::STATUS_FAILED,
            AdmissionPayment::STATUS_CANCELLED,
        ];
    }
}
