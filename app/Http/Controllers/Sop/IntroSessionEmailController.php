<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\IntroSessionEmailLog;
use App\Services\IntroSessionConfirmationMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IntroSessionEmailController extends Controller
{
    public function __construct(private IntroSessionConfirmationMailer $mailer) {}

    public function index(Request $request): View
    {
        $allowed = bns_intro_session_allowed_numbers();
        $session = (int) $request->query('session', $allowed[0] ?? 1);
        if (! in_array($session, $allowed, true)) {
            $session = $allowed[0] ?? 1;
        }

        $filters = $this->listFiltersFromRequest($request);
        $participants = $this->participantsForSession($session, $filters);
        $event = bns_introduction_session($session) ?? [];
        $attendance = bns_session_attendance_breakdown($session);
        $listView = $filters['list'];

        $listParticipants = match ($listView) {
            'present' => $this->filterParticipantsByIds($participants, $attendance['present_rows']),
            'absent' => $this->filterParticipantsByIds($participants, $attendance['absent_rows']),
            default => $participants,
        };

        $allForOptions = $this->participantsForSession($session, [
            'search' => '',
            'form_source' => '',
            'program' => '',
            'date_from' => '',
            'date_to' => '',
            'list' => 'all',
        ]);

        $stats = [
            'with_email' => $listParticipants->filter(fn (ContactInquiry $row) => filled($row->email))->count(),
            'filtered' => $listParticipants->count(),
            'registered' => $attendance['registered'],
            'present' => $attendance['present'],
            'absent' => $attendance['absent'],
        ];
        foreach ($allowed as $sessionNo) {
            $stats['session_'.$sessionNo] = $this->participantsForSession($sessionNo)->count();
        }

        $logs = IntroSessionEmailLog::query()
            ->with('sender')
            ->when($request->filled('log_session'), function ($query) use ($request, $allowed) {
                $logSession = (int) $request->query('log_session');
                if (in_array($logSession, $allowed, true)) {
                    $query->where('session_number', $logSession);
                }
            })
            ->when($request->filled('log_status'), function ($query) use ($request) {
                $status = (string) $request->query('log_status');
                if (in_array($status, [
                    IntroSessionEmailLog::STATUS_SENT,
                    IntroSessionEmailLog::STATUS_SKIPPED,
                    IntroSessionEmailLog::STATUS_FAILED,
                ], true)) {
                    $query->where('status', $status);
                }
            })
            ->orderByDesc('sent_at')
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        $emailTemplates = bns_message_email_templates();
        $selectedTemplate = (string) $request->query('template', IntroSessionConfirmationMailer::TEMPLATE_WELCOME);
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
                'layout' => (($template['type'] ?? '') === 'welcome') ? 'welcome' : '',
                'preview' => '',
                'preview_html' => '',
            ];
        }

        $selectedFull = null;
        if ($selectedTemplate !== '') {
            if ($selectedTemplate === IntroSessionConfirmationMailer::TEMPLATE_WELCOME) {
                $selectedFull = [
                    'id' => $selectedTemplate,
                    'stage' => 'System',
                    'title' => 'Welcome Confirmation + Calendar',
                    'layout' => 'welcome',
                    'preview' => trim(
                        (string) config('intro_session_email.intro', '')
                        ."\n\n"
                        .($event['date'] ?? '')
                        .' · '
                        .($event['time'] ?? '')
                    ),
                    'preview_html' => view('emails.partials.session-venue-card', [
                        'venueCard' => bns_intro_session_venue_card($event),
                    ])->render(),
                ];
            } else {
                $full = bns_message_email_template($selectedTemplate);
                if ($full !== null) {
                    $selectedFull = [
                        'id' => $selectedTemplate,
                        'stage' => (string) ($full['stage'] ?? ''),
                        'title' => (string) ($full['title'] ?? ''),
                        'layout' => (string) ($full['layout'] ?? ''),
                        'preview' => (string) ($full['whatsapp'] ?? ''),
                        'preview_html' => (string) ($full['rich_html'] ?? $full['body_html'] ?? ''),
                    ];
                }
            }

            if ($selectedFull !== null) {
                $templatePreviews[$selectedTemplate] = $selectedFull;
            }
        }

        return view('sop.intro-session-emails.index', [
            'participants' => $listParticipants,
            'allParticipants' => $participants,
            'session' => $session,
            'sessions' => bns_introduction_sessions(),
            'allowedSessions' => $allowed,
            'emailTemplates' => $emailTemplates,
            'selectedTemplate' => $selectedTemplate,
            'templatePreviews' => $templatePreviews,
            'previewUrl' => route('controlpanel.intro-session-emails.preview'),
            'venueCard' => bns_intro_session_venue_card($event),
            'search' => $filters['search'],
            'formSourceFilter' => $filters['form_source'],
            'programFilter' => $filters['program'],
            'dateFrom' => $filters['date_from'],
            'dateTo' => $filters['date_to'],
            'listView' => $listView,
            'formSourceOptions' => config('reporting.form_sources', []),
            'programOptions' => $allForOptions
                ->pluck('interested_program')
                ->map(fn ($v) => trim((string) $v))
                ->filter()
                ->unique()
                ->sort()
                ->values(),
            'event' => $event,
            'stats' => $stats,
            'attendance' => $attendance,
            'logs' => $logs,
            'logSession' => $request->query('log_session'),
            'logStatus' => $request->query('log_status'),
            'filterQuery' => array_filter([
                'session' => $session,
                'list' => $listView,
                'q' => $filters['search'] !== '' ? $filters['search'] : null,
                'form_source' => $filters['form_source'] !== '' ? $filters['form_source'] : null,
                'program' => $filters['program'] !== '' ? $filters['program'] : null,
                'date_from' => $filters['date_from'] !== '' ? $filters['date_from'] : null,
                'date_to' => $filters['date_to'] !== '' ? $filters['date_to'] : null,
                'template' => $selectedTemplate !== '' ? $selectedTemplate : null,
            ], fn ($v) => $v !== null && $v !== ''),
        ]);
    }

    public function preview(Request $request): \Illuminate\Http\JsonResponse
    {
        $templateId = trim((string) $request->query('template', ''));
        $session = (int) $request->query('session', bns_intro_session_allowed_numbers()[0] ?? 1);
        $event = bns_introduction_session($session) ?? [];

        if ($templateId === '' || $templateId === IntroSessionConfirmationMailer::TEMPLATE_WELCOME) {
            return response()->json([
                'id' => IntroSessionConfirmationMailer::TEMPLATE_WELCOME,
                'stage' => 'System',
                'title' => 'Welcome Confirmation + Calendar',
                'layout' => 'welcome',
                'preview' => (string) config('intro_session_email.intro', ''),
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
            'layout' => (string) ($full['layout'] ?? ''),
            'preview' => (string) ($full['whatsapp'] ?? ''),
            'preview_html' => (string) ($full['rich_html'] ?? $full['body_html'] ?? ''),
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $allowed = bns_intro_session_allowed_numbers();
        $templateIds = collect(bns_message_email_templates())->pluck('id')->all();
        $validated = $request->validate([
            'session' => ['required', 'integer', 'in:'.implode(',', $allowed)],
            'template' => ['required', 'string', 'in:'.implode(',', $templateIds)],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:contact_inquiries,id'],
        ], [
            'template.required' => 'Please select an email template.',
            'template.in' => 'Please select a valid email template.',
            'ids.required' => 'Please select at least one participant.',
            'ids.min' => 'Please select at least one participant.',
        ]);

        $session = (int) $validated['session'];
        $templateId = (string) $validated['template'];
        $templateMeta = bns_message_email_template($templateId);
        $templateTitle = (string) ($templateMeta['title'] ?? $templateId);

        $allowedIds = $this->participantsForSession($session)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $selectedIds = collect($validated['ids'])
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => in_array($id, $allowedIds, true))
            ->unique()
            ->values();

        $sent = 0;
        $skipped = 0;
        $failed = 0;
        $batchKey = Str::lower(Str::random(16));
        $sentBy = $request->user()?->id;
        $now = now();

        $inquiries = ContactInquiry::query()
            ->whereIn('id', $selectedIds)
            ->get()
            ->keyBy('id');

        foreach ($selectedIds as $id) {
            $inquiry = $inquiries->get($id);
            if (! $inquiry) {
                $skipped++;
                continue;
            }

            $email = trim((string) $inquiry->email);
            $baseLog = [
                'contact_inquiry_id' => $inquiry->id,
                'session_number' => $session,
                'template_key' => $templateId,
                'template_title' => $templateTitle,
                'registration_number' => $inquiry->registration_number,
                'full_name' => $inquiry->full_name,
                'email' => $email !== '' ? $email : null,
                'mobile' => $inquiry->mobile,
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
                'registration_number' => $inquiry->registration_number,
                'full_name' => $inquiry->full_name,
                'email' => $email,
                'mobile' => $inquiry->mobile,
                'interested_program' => $inquiry->interested_program,
            ], $session, $templateId);

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

        $event = bns_introduction_session($session);
        $dateLabel = $event['date'] ?? ('Session '.$session);

        return redirect()
            ->route('controlpanel.intro-session-emails.index', array_filter([
                'session' => $session,
                'template' => $templateId,
                'list' => $request->input('list', 'all'),
                'q' => $request->input('q'),
                'form_source' => $request->input('form_source'),
                'program' => $request->input('program'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ], fn ($v) => $v !== null && $v !== ''))
            ->with('status', "Template \"{$templateTitle}\" for {$dateLabel}: Sent {$sent}, Skipped {$skipped}, Failed {$failed}. Log saved.");
    }

    public function export(Request $request): StreamedResponse
    {
        $allowed = bns_intro_session_allowed_numbers();
        $session = (int) $request->query('session', $allowed[0] ?? 1);
        if (! in_array($session, $allowed, true)) {
            $session = $allowed[0] ?? 1;
        }

        $filters = $this->listFiltersFromRequest($request);
        $listView = $filters['list'];
        $participants = $this->participantsForSession($session, $filters);
        $attendance = bns_session_attendance_breakdown($session);

        $participants = match ($listView) {
            'present' => $this->filterParticipantsByIds($participants, $attendance['present_rows']),
            'absent' => $this->filterParticipantsByIds($participants, $attendance['absent_rows']),
            default => $participants,
        };

        $presentLookup = $attendance['present_rows']
            ->mapWithKeys(function (ContactInquiry $row) {
                $mobile = ContactInquiry::normalizeMobile($row->mobile);

                return $mobile !== '' ? [$mobile => true] : [];
            })
            ->all();

        $event = bns_introduction_session($session) ?? [];
        $sessionDate = (string) ($event['date'] ?? ('Session '.$session));
        $sessionTitle = (string) ($event['title'] ?? ('Introduction Session '.$session));
        $listSuffix = $listView === 'all' ? 'email-list' : $listView.'-list';
        $filename = 'bns-session-'.$session.'-'.$listSuffix.'-'.now()->format('Ymd-His').'.csv';

        $headers = [
            'Sr. No.',
            'Session',
            'Session Date',
            'Attendance',
            'Full Name',
            'Email',
            'Mobile',
            'Reference No.',
            'Form Source',
            'Program',
            'Has Email',
            'Registered At',
        ];

        return response()->streamDownload(function () use ($participants, $headers, $session, $sessionDate, $sessionTitle, $presentLookup) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            $sr = 0;
            foreach ($participants as $participant) {
                $sr++;
                $email = trim((string) ($participant->email ?? ''));
                $hasEmail = $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
                $mobile = ContactInquiry::normalizeMobile($participant->mobile);
                $attendanceLabel = ($mobile !== '' && isset($presentLookup[$mobile])) ? 'Present' : 'Absent';

                fputcsv($handle, [
                    $sr,
                    $sessionTitle,
                    $sessionDate,
                    $attendanceLabel,
                    $participant->full_name ?? '',
                    $email,
                    $participant->mobile ?? '',
                    $participant->registration_number ?? '',
                    $participant->formSourceLabel(),
                    $participant->interested_program ?? '',
                    $hasEmail ? 'Yes' : 'No',
                    $participant->created_at?->format('d M Y, h:i A') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    /**
     * @return array{search: string, form_source: string, program: string, date_from: string, date_to: string, list: string}
     */
    private function listFiltersFromRequest(Request $request): array
    {
        $list = (string) $request->query('list', 'all');
        if (! in_array($list, ['all', 'present', 'absent'], true)) {
            $list = 'all';
        }

        $formSource = trim((string) $request->query('form_source', ''));
        $allowedSources = array_keys(config('reporting.form_sources', []));
        if ($formSource !== '' && ! in_array($formSource, $allowedSources, true)) {
            $formSource = '';
        }

        return [
            'search' => trim((string) $request->query('q', '')),
            'form_source' => $formSource,
            'program' => trim((string) $request->query('program', '')),
            'date_from' => trim((string) $request->query('date_from', '')),
            'date_to' => trim((string) $request->query('date_to', '')),
            'list' => $list,
        ];
    }

    /**
     * Unique intro-session participants for a session (one row per mobile).
     *
     * @param  array{search?: string, form_source?: string, program?: string, date_from?: string, date_to?: string}|string  $filters
     * @return Collection<int, ContactInquiry>
     */
    private function participantsForSession(int $session, array|string $filters = []): Collection
    {
        if (is_string($filters)) {
            $filters = [
                'search' => $filters,
                'form_source' => '',
                'program' => '',
                'date_from' => '',
                'date_to' => '',
            ];
        }

        $search = trim((string) ($filters['search'] ?? ''));
        $formSource = trim((string) ($filters['form_source'] ?? ''));
        $program = trim((string) ($filters['program'] ?? ''));
        $dateFrom = trim((string) ($filters['date_from'] ?? ''));
        $dateTo = trim((string) ($filters['date_to'] ?? ''));

        $map = bns_reporting_session_mobile_map(onlyIntro: false);

        $ids = collect($map)
            ->filter(fn (array $row) => (int) ($row['session'] ?? 0) === $session)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return collect();
        }

        $query = ContactInquiry::query()
            ->whereIn('id', $ids)
            ->orderBy('id');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhere('interested_program', 'like', "%{$search}%");
            });
        }

        if ($formSource !== '') {
            if ($formSource === 'unknown') {
                $query->where(function ($builder) {
                    $builder->whereNull('form_source')->orWhere('form_source', '');
                });
            } else {
                $query->where('form_source', $formSource);
            }
        }

        if ($program !== '') {
            $query->where('interested_program', $program);
        }

        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query->get();
    }

    /**
     * @param  Collection<int, ContactInquiry>  $participants
     * @param  Collection<int, ContactInquiry>  $subset
     * @return Collection<int, ContactInquiry>
     */
    private function filterParticipantsByIds(Collection $participants, Collection $subset): Collection
    {
        $ids = $subset->pluck('id')->map(fn ($id) => (int) $id)->all();

        return $participants
            ->filter(fn (ContactInquiry $row) => in_array((int) $row->id, $ids, true))
            ->values();
    }
}
