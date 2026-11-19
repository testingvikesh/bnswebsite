<?php

namespace App\Http\Controllers;

use App\Models\CrmAssignment;
use App\Models\CrmFollowup;
use App\Services\CrmAttendanceSync;
use App\Services\HomeImageService;
use App\Support\CrmLeadStatus;
use App\Support\CrmPortal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CrmDeskController extends Controller
{
    public function __construct(
        private HomeImageService $homeImages,
        private CrmAttendanceSync $attendanceSync,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        if (CrmPortal::isAdmin($request)) {
            return redirect()->route('crm.dashboard');
        }

        $employee = CrmPortal::employee($request);
        if (! $employee) {
            abort(403);
        }

        $allowed = bns_intro_session_allowed_numbers();
        $sessionFilter = (int) $request->query('session', 0);
        if (in_array($sessionFilter, $allowed, true)) {
            return redirect()->route('crm.desk.session', array_filter([
                'session' => $sessionFilter,
                'q' => trim((string) $request->query('q', '')) ?: null,
                'status' => $request->query('status'),
                'call' => $request->query('call'),
                'call_status' => $request->query('call_status'),
            ], fn ($value) => $value !== null && $value !== '' && $value !== 'all'));
        }

        $filters = $this->deskFilters($request);
        $allAssigned = $this->employeeAssignments($employee->id);
        $totals = $this->assignmentTotals($allAssigned, (int) $employee->id);
        $sessions = $this->employeeSessionCards($allAssigned, $allowed);
        $filtered = $this->applyDeskFilters($allAssigned, $filters);
        $showGrouped = $filters['search'] !== ''
            || $filters['status'] !== 'all'
            || $filters['call'] !== ''
            || $filters['callStatus'] !== '';

        $grouped = $showGrouped
            ? $filtered->groupBy(fn (CrmAssignment $row) => (int) $row->session_number)
            : collect();

        return view('crm.desk', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'employee' => $employee,
            'sessions' => $sessions,
            'grouped' => $grouped,
            'showGrouped' => $showGrouped,
            'search' => $filters['search'],
            'status' => $filters['status'],
            'call' => $filters['call'],
            'callStatus' => $filters['callStatus'],
            'callStatusCounts' => $totals['callStatusCounts'],
            'followupStatusOptions' => CrmFollowup::statusOptions(),
            'allowedSessions' => $allowed,
            'isAdmin' => false,
            'totals' => $totals,
        ]);
    }

    public function specialCalling(Request $request): View|RedirectResponse
    {
        if (CrmPortal::isAdmin($request)) {
            return redirect()->route('crm.special-calling');
        }

        $employee = CrmPortal::employee($request);
        if (! $employee) {
            abort(403);
        }

        $allowed = bns_intro_session_allowed_numbers();
        $sessionFilter = (int) $request->query('session', 0);
        if (! in_array($sessionFilter, $allowed, true)) {
            $sessionFilter = 0;
        }

        $filters = $this->deskFilters($request);
        $allAssigned = $this->employeeAssignments($employee->id);
        $specialAll = $allAssigned
            ->filter(fn (CrmAssignment $row) => (string) $row->attendance_status === 'absent')
            ->values();

        $sessionTotals = [];
        foreach ($allowed as $sessionNo) {
            $sessionTotals[$sessionNo] = $specialAll->where('session_number', $sessionNo)->count();
        }

        $scoped = $sessionFilter > 0
            ? $specialAll->where('session_number', $sessionFilter)->values()
            : $specialAll;

        $callDone = $scoped->filter(fn (CrmAssignment $row) => $row->hasCallDone())->count();
        $remain = $scoped->count() - $callDone;

        $followupStatusOptions = CrmFollowup::statusOptions();
        $followupNumbers = CrmFollowup::numbers();
        $callRound = (int) $request->query('call_round', 0);
        if (! in_array($callRound, $followupNumbers, true)) {
            $callRound = 0;
        }

        $callStatusCounts = [];
        foreach (array_keys($followupStatusOptions) as $statusKey) {
            $callStatusCounts[$statusKey] = $scoped
                ->filter(fn (CrmAssignment $row) => $row->lastCallStatus() === $statusKey)
                ->count();
        }

        $callRoundCounts = [];
        foreach ($followupNumbers as $roundNo) {
            $callRoundCounts[$roundNo] = $scoped
                ->filter(fn (CrmAssignment $row) => ($fu = $row->followup($roundNo)) && $fu->isDone())
                ->count();
        }
        $completedFollowupSum = $scoped->sum(fn (CrmAssignment $row) => $row->completedFollowups());
        $callAverage = $scoped->count() > 0
            ? round($completedFollowupSum / $scoped->count(), 1)
            : 0.0;

        $filtered = $this->applyDeskFilters($scoped, array_merge($filters, [
            'status' => 'all',
            'callRound' => $callRound,
        ]));

        return view('crm.desk-special-calling', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'employee' => $employee,
            'results' => $filtered,
            'search' => $filters['search'],
            'call' => $filters['call'],
            'callStatus' => $filters['callStatus'],
            'callRound' => $callRound,
            'callStatusCounts' => $callStatusCounts,
            'callRoundCounts' => $callRoundCounts,
            'callAverage' => $callAverage,
            'followupStatusOptions' => $followupStatusOptions,
            'followupNumbers' => $followupNumbers,
            'allowedSessions' => $allowed,
            'sessionFilter' => $sessionFilter,
            'sessionTotals' => $sessionTotals,
            'allCount' => $specialAll->count(),
            'scopedCount' => $scoped->count(),
            'callDone' => $callDone,
            'remain' => $remain,
            'isAdmin' => false,
        ]);
    }

    public function session(Request $request, int $session): View|RedirectResponse
    {
        if (CrmPortal::isAdmin($request)) {
            return redirect()->route('crm.session', $session);
        }

        $employee = CrmPortal::employee($request);
        if (! $employee) {
            abort(403);
        }

        $allowed = bns_intro_session_allowed_numbers();
        if (! in_array($session, $allowed, true)) {
            abort(404);
        }

        $filters = $this->deskFilters($request);
        $allAssigned = $this->employeeAssignments($employee->id, $session);
        $totals = $this->assignmentTotals($allAssigned, (int) $employee->id, $session);
        $filtered = $this->applyDeskFilters($allAssigned, $filters);
        $presentRows = $filtered->where('attendance_status', 'present')->values();
        $absentRows = $filtered->where('attendance_status', 'absent')->values();
        $event = bns_introduction_session($session) ?? [
            'title' => bns_intro_session_label($session),
            'date' => '',
            'time' => '',
        ];

        return view('crm.desk-session', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'employee' => $employee,
            'sessionNo' => $session,
            'event' => $event,
            'presentRows' => $presentRows,
            'absentRows' => $absentRows,
            'search' => $filters['search'],
            'status' => $filters['status'],
            'call' => $filters['call'],
            'callStatus' => $filters['callStatus'],
            'callStatusCounts' => $totals['callStatusCounts'],
            'followupStatusOptions' => CrmFollowup::statusOptions(),
            'isAdmin' => false,
            'totals' => $totals,
        ]);
    }

    public function show(Request $request, CrmAssignment $assignment): View|RedirectResponse
    {
        $this->assertOwns($request, $assignment);
        $assignment->load(['inquiry', 'followups', 'employee']);
        $this->attendanceSync->syncCollection(collect([$assignment]));

        if (! CrmPortal::isAdmin($request) && CrmLeadStatus::isConfirmed($assignment->inquiry)) {
            return redirect()
                ->route('crm.desk.session', (int) $assignment->session_number)
                ->with('status', ($assignment->inquiry->full_name ?? 'This member').' already paid, so they are not in the call list.');
        }

        $followups = [];
        foreach (CrmFollowup::numbers() as $n) {
            $followups[$n] = $assignment->followup($n);
        }

        return view('crm.member', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'assignment' => $assignment,
            'inquiry' => $assignment->inquiry,
            'followups' => $followups,
            'statusOptions' => CrmFollowup::statusOptions(),
            'isAdmin' => CrmPortal::isAdmin($request),
            'employee' => $assignment->employee,
        ]);
    }

    public function saveFollowup(Request $request, CrmAssignment $assignment, int $followup): RedirectResponse
    {
        $this->assertOwns($request, $assignment);

        if ($followup < 1 || $followup > CrmFollowup::maxNumber()) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(array_keys(CrmFollowup::statusOptions()))],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $row = CrmFollowup::query()->updateOrCreate(
            [
                'crm_assignment_id' => $assignment->id,
                'followup_no' => $followup,
            ],
            [
                'status' => $validated['status'],
                'note' => trim((string) ($validated['note'] ?? '')),
                'called_at' => $validated['status'] === CrmFollowup::STATUS_PENDING ? null : now(),
            ]
        );

        $assignment->loadMissing('inquiry');
        if ($validated['status'] === CrmFollowup::STATUS_ADMITTED && $assignment->inquiry) {
            CrmLeadStatus::confirm($assignment->inquiry);

            if (! CrmPortal::isAdmin($request)) {
                return redirect()
                    ->route('crm.desk.session', (int) $assignment->session_number)
                    ->with('status', 'Follow-up '.$row->followup_no.' saved. This member is hidden from the call list.');
            }
        }

        return back()->with('status', 'Follow-up '.$row->followup_no.' saved.');
    }

    private function assertOwns(Request $request, CrmAssignment $assignment): void
    {
        if (CrmPortal::isAdmin($request)) {
            return;
        }

        $employee = CrmPortal::employee($request);
        if (! $employee || (int) $assignment->crm_employee_id !== (int) $employee->id) {
            abort(403, 'This member is not assigned to you.');
        }
    }

    /**
     * @return array{search: string, status: string, call: string, callStatus: string}
     */
    private function deskFilters(Request $request): array
    {
        $status = strtolower(trim((string) $request->query('status', 'all')));
        if (! in_array($status, ['all', 'present', 'absent'], true)) {
            $status = 'all';
        }
        $call = strtolower(trim((string) $request->query('call', '')));
        if (! in_array($call, ['done', 'remain'], true)) {
            $call = '';
        }
        $callStatusOptions = CrmFollowup::statusOptions();
        $callStatus = trim((string) $request->query('call_status', ''));
        if ($callStatus !== '' && ! array_key_exists($callStatus, $callStatusOptions)) {
            $callStatus = '';
        }

        return [
            'search' => trim((string) $request->query('q', '')),
            'status' => $status,
            'call' => $call,
            'callStatus' => $callStatus,
        ];
    }

    private function employeeAssignments(int $employeeId, int $session = 0)
    {
        $query = CrmAssignment::query()
            ->with(['inquiry', 'followups', 'employee'])
            ->where('crm_employee_id', $employeeId)
            ->latest('assigned_at');

        if ($session > 0) {
            $query->where('session_number', $session);
        }

        CrmLeadStatus::excludeConfirmed($query);

        return $this->attendanceSync->syncCollection($query->get());
    }

    /**
     * @param  \Illuminate\Support\Collection<int, CrmAssignment>  $assignments
     * @return array<string, mixed>
     */
    private function assignmentTotals($assignments, int $employeeId, int $session = 0): array
    {
        $callStatusOptions = CrmFollowup::statusOptions();
        $paidCount = 0;
        if (Schema::hasTable('admission_payments')) {
            $paidCount = CrmLeadStatus::filterPaymentsForEmployee(
                CrmLeadStatus::successfulPaymentsQuery(),
                $employeeId,
                $session
            )->count();
        }

        $callStatusCounts = [];
        foreach (array_keys($callStatusOptions) as $key) {
            $callStatusCounts[$key] = $assignments
                ->filter(fn (CrmAssignment $row) => $row->lastCallStatus() === $key)
                ->count();
        }

        return [
            'assigned' => $assignments->count(),
            'present' => $assignments->where('attendance_status', 'present')->count(),
            'absent' => $assignments->where('attendance_status', 'absent')->count(),
            'followups_done' => $assignments->sum(fn (CrmAssignment $row) => $row->completedFollowups()),
            'call_done' => $assignments->filter(fn (CrmAssignment $row) => $row->hasCallDone())->count(),
            'remain' => $assignments->filter(fn (CrmAssignment $row) => ! $row->hasCallDone())->count(),
            'paid' => $paidCount,
            'callStatusCounts' => $callStatusCounts,
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, CrmAssignment>  $assignments
     * @param  array{search: string, status: string, call: string, callStatus: string, callRound?: int}  $filters
     * @return \Illuminate\Support\Collection<int, CrmAssignment>
     */
    private function applyDeskFilters($assignments, array $filters)
    {
        $rows = $assignments;

        if ($filters['status'] !== 'all') {
            $rows = $rows->where('attendance_status', $filters['status'])->values();
        }

        if ($filters['search'] !== '') {
            $needle = mb_strtolower($filters['search']);
            $rows = $rows->filter(function (CrmAssignment $assignment) use ($needle) {
                $row = $assignment->inquiry;
                if (! $row) {
                    return false;
                }
                $hay = mb_strtolower(implode(' ', array_filter([
                    (string) $row->full_name,
                    (string) $row->email,
                    (string) $row->mobile,
                    (string) $row->registration_number,
                    (string) $row->city,
                ])));

                return str_contains($hay, $needle);
            })->values();
        }

        if ($filters['call'] === 'done') {
            $rows = $rows->filter(fn (CrmAssignment $row) => $row->hasCallDone())->values();
        } elseif ($filters['call'] === 'remain') {
            $rows = $rows->filter(fn (CrmAssignment $row) => ! $row->hasCallDone())->values();
        }

        if ($filters['callStatus'] !== '') {
            $rows = $rows->filter(fn (CrmAssignment $row) => $row->lastCallStatus() === $filters['callStatus'])->values();
        }

        $callRound = (int) ($filters['callRound'] ?? 0);
        if ($callRound > 0) {
            $rows = $rows->filter(function (CrmAssignment $row) use ($callRound) {
                $fu = $row->followup($callRound);

                return $fu && $fu->isDone();
            })->values();
        }

        return $rows;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, CrmAssignment>  $assignments
     * @param  array<int, int>  $allowed
     * @return array<int, array<string, mixed>>
     */
    private function employeeSessionCards($assignments, array $allowed): array
    {
        $cards = [];

        foreach ($allowed as $sessionNo) {
            $rows = $assignments->where('session_number', $sessionNo);
            $event = bns_introduction_session($sessionNo) ?? [];
            $assigned = $rows->count();
            $present = $rows->where('attendance_status', 'present')->count();
            $absent = $rows->where('attendance_status', 'absent')->count();

            $cards[] = [
                'number' => $sessionNo,
                'title' => bns_intro_session_label($sessionNo, is_array($event) ? $event : null),
                'date' => (string) ($event['date'] ?? ''),
                'time' => (string) ($event['time'] ?? ''),
                'assigned' => $assigned,
                'present' => $present,
                'absent' => $absent,
                'call_done' => $rows->filter(fn (CrmAssignment $row) => $row->hasCallDone())->count(),
                'remain' => $rows->filter(fn (CrmAssignment $row) => ! $row->hasCallDone())->count(),
                'present_pct' => $assigned > 0 ? (int) round(($present / $assigned) * 100) : 0,
            ];
        }

        return $cards;
    }
}
