<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\CrmAssignment;
use App\Models\CrmEmployee;
use App\Models\CrmSpotAdmission;
use App\Models\SessionAttendance;
use App\Services\CrmAllocationService;
use App\Services\HomeImageService;
use App\Support\CrmLeadStatus;
use App\Support\CrmPortal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CrmController extends Controller
{
    public function __construct(private HomeImageService $homeImages) {}

    public function loginForm(Request $request): View|RedirectResponse
    {
        if (CrmPortal::isAdmin($request)) {
            return redirect()->route('crm.dashboard');
        }
        if (CrmPortal::isEmployee($request)) {
            return redirect()->route('crm.desk');
        }

        return view('crm.login', [
            'heroImage' => $this->heroImage(),
            'page' => config('crm.page', []),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:100'],
        ], [
            'username.required' => 'Please enter username.',
            'password.required' => 'Please enter password.',
        ]);

        $username = trim($validated['username']);
        $password = (string) $validated['password'];

        $expectedUser = (string) config('crm.username', 'bnscrm');
        $expectedPass = (string) config('crm.password', '');
        $adminOk = hash_equals(mb_strtolower($expectedUser), mb_strtolower($username))
            && $this->passwordMatches($expectedPass, $password);

        if ($adminOk) {
            CrmPortal::loginAdmin($request);

            return redirect()->route('crm.dashboard');
        }

        $employee = CrmEmployee::query()
            ->whereRaw('LOWER(username) = ?', [mb_strtolower($username)])
            ->first();

        if ($employee && $employee->is_active && $employee->passwordMatches($password)) {
            CrmPortal::loginEmployee($request, $employee);

            return redirect()->route('crm.desk');
        }

        if ($employee && ! $employee->is_active) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'This employee account is inactive.']);
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'Invalid username or password.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        CrmPortal::logout($request);

        return redirect()->route('crm.login')->with('status', 'You have been logged out.');
    }

    public function registerForm(Request $request): View|RedirectResponse
    {
        if (CrmPortal::isAdmin($request)) {
            return redirect()->route('crm.dashboard');
        }
        if (CrmPortal::isEmployee($request)) {
            return redirect()->route('crm.desk');
        }

        return view('crm.register', [
            'heroImage' => $this->heroImage(),
            'page' => config('crm.page', []),
            'facilities' => config('admission.centres', []),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('crm_employees')) {
            return back()->with('error', 'Employee registration is not available.');
        }

        $adminUsername = (string) config('crm.username', 'bnscrm');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => [
                'required',
                'string',
                'max:80',
                'alpha_dash',
                Rule::unique('crm_employees', 'username'),
                function (string $attribute, mixed $value, \Closure $fail) use ($adminUsername) {
                    if (strcasecmp(trim((string) $value), $adminUsername) === 0) {
                        $fail('This username is reserved. Choose another.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:6', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'facility' => ['nullable', 'string', 'max:120'],
        ], [
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'username.unique' => 'This username is already used. Please login.',
            'email.email' => 'Enter a valid email ID.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        $payload = [
            'name' => trim($validated['name']),
            'username' => trim($validated['username']),
            'password' => $validated['password'],
            'mobile' => trim((string) ($validated['mobile'] ?? '')),
            'is_active' => true,
        ];

        if (Schema::hasColumn('crm_employees', 'email')) {
            $payload['email'] = trim((string) ($validated['email'] ?? '')) ?: null;
        }
        if (Schema::hasColumn('crm_employees', 'facility')) {
            $payload['facility'] = trim((string) ($validated['facility'] ?? '')) ?: null;
        }

        $employee = CrmEmployee::query()->create($payload);
        CrmPortal::loginEmployee($request, $employee);

        return redirect()->route('crm.desk')->with('status', 'Account created. You are logged in.');
    }

    public function dashboard(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = strtolower(trim((string) $request->query('status', '')));
        if (! in_array($status, ['all', 'present', 'absent', 'paid', 'assigned'], true)) {
            $status = '';
        }

        $allowed = bns_intro_session_allowed_numbers();
        $sessionFilter = (int) $request->query('session', 0);
        if (! in_array($sessionFilter, $allowed, true)) {
            $sessionFilter = 0;
        }

        $sessions = $this->sessionCards();
        $scopedSessions = $sessionFilter > 0
            ? array_values(array_filter($sessions, fn (array $item) => (int) $item['number'] === $sessionFilter))
            : $sessions;

        $showList = $search !== '' || $status !== '' || $sessionFilter > 0;
        $results = collect();
        if ($showList) {
            $results = $status === 'assigned'
                ? $this->assignedHits($sessionFilter, $search)
                : $this->searchAcrossSessions($search, $scopedSessions);

            if (in_array($status, ['present', 'absent', 'paid'], true)) {
                $results = $results->where('status', $status)->values();
            }
        }

        $totalsSource = collect($scopedSessions);

        return view('crm.dashboard', [
            'heroImage' => $this->heroImage(),
            'page' => config('crm.page', []),
            'sessions' => $sessions,
            'search' => $search,
            'status' => $status,
            'sessionFilter' => $sessionFilter,
            'allowed' => $allowed,
            'results' => $results,
            'showList' => $showList,
            'isAdmin' => true,
            'totals' => [
                'sessions' => count($sessions),
                'registered' => $totalsSource->sum('registered'),
                'present' => $totalsSource->sum('present'),
                'absent' => $totalsSource->sum('absent'),
                'paid' => $totalsSource->sum('paid'),
                'employees' => Schema::hasTable('crm_employees')
                    ? CrmEmployee::query()->where('is_active', true)->count()
                    : 0,
                'assigned' => Schema::hasTable('crm_assignments')
                    ? CrmAssignment::query()
                        ->when($sessionFilter > 0, fn ($query) => $query->where('session_number', $sessionFilter))
                        ->count()
                    : 0,
                'today_attendance' => $this->todayAttendanceCount(),
            ],
        ]);
    }

    public function todayAttendance(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $view = strtolower(trim((string) $request->query('view', 'all')));
        if (! in_array($view, ['all', 'spot'], true)) {
            $view = 'all';
        }

        $allowed = bns_intro_session_allowed_numbers();
        $sessionFilter = (int) $request->query('session', 0);
        if (! in_array($sessionFilter, $allowed, true)) {
            $sessionFilter = 0;
        }

        $day = $this->attendanceDay($request);
        $groups = $this->todayAttendanceGroups($search, $day);
        $sessionTotals = [];
        foreach ($allowed as $sessionNo) {
            $sessionTotals[$sessionNo] = (int) optional($groups->get($sessionNo))->count();
        }

        if ($sessionFilter > 0) {
            $groups = collect([$sessionFilter => $groups->get($sessionFilter) ?? collect()]);
        }

        $spots = $this->todaySpotAdmissions($search, $day);
        $isToday = $day->isSameDay(now('Asia/Kolkata'));

        return view('crm.today-attendance', [
            'heroImage' => $this->heroImage(),
            'page' => config('crm.page', []),
            'search' => $search,
            'view' => $view,
            'groups' => $groups,
            'spots' => $spots,
            'total' => array_sum($sessionTotals),
            'sessionTotals' => $sessionTotals,
            'allowedSessions' => $allowed,
            'sessionFilter' => $sessionFilter,
            'spotTotal' => $spots->count(),
            'dateLabel' => $day->format('d M Y'),
            'dateValue' => $day->toDateString(),
            'todayValue' => now('Asia/Kolkata')->toDateString(),
            'isToday' => $isToday,
            'isAdmin' => true,
        ]);
    }

    public function session(Request $request, int $session): View
    {
        $allowed = bns_intro_session_allowed_numbers();
        if (! in_array($session, $allowed, true)) {
            abort(404);
        }

        $search = trim((string) $request->query('q', ''));
        $status = strtolower(trim((string) $request->query('status', 'all')));
        if (! in_array($status, ['all', 'present', 'absent', 'paid'], true)) {
            $status = 'all';
        }
        $team = trim((string) $request->query('team', ''));

        $event = bns_introduction_session($session) ?? [
            'title' => 'Introduction Session '.$session,
            'date' => '',
            'time' => '',
        ];
        $buckets = $this->crmAttendanceBuckets(bns_session_attendance_breakdown($session));
        $presentRows = $this->filterRows($buckets['present_rows'], $search);
        $absentRows = $this->filterRows($buckets['absent_rows'], $search);
        $paidRows = $this->filterRows($buckets['paid_rows'], $search);

        $assignments = Schema::hasTable('crm_assignments')
            ? CrmAssignment::query()
                ->with(['employee', 'followups'])
                ->where('session_number', $session)
                ->get()
                ->keyBy('contact_inquiry_id')
            : collect();

        $employees = Schema::hasTable('crm_employees')
            ? CrmEmployee::query()->where('is_active', true)->orderBy('name')->get()
            : collect();

        $teamCounts = [];
        foreach ($employees as $employee) {
            $teamCounts[(int) $employee->id] = $assignments->where('crm_employee_id', (int) $employee->id)->count();
        }
        $unassignedCount = collect($presentRows)
            ->concat($absentRows)
            ->concat($paidRows)
            ->filter(fn ($row) => ! $assignments->has($row->id))
            ->count();

        $allowedTeamIds = $employees->pluck('id')->map(fn ($id) => (string) $id)->all();
        if ($team !== '' && $team !== 'unassigned' && ! in_array($team, $allowedTeamIds, true)) {
            $team = '';
        }

        $filterByTeam = function ($rows) use ($assignments, $team) {
            if ($team === '') {
                return $rows->values();
            }
            if ($team === 'unassigned') {
                return $rows->filter(fn ($row) => ! $assignments->has($row->id))->values();
            }

            $employeeId = (int) $team;

            return $rows
                ->filter(fn ($row) => (int) optional($assignments->get($row->id))->crm_employee_id === $employeeId)
                ->values();
        };

        $presentRows = $filterByTeam($presentRows);
        $absentRows = $filterByTeam($absentRows);
        $paidRows = $filterByTeam($paidRows);

        $listRows = match ($status) {
            'present' => $presentRows,
            'absent' => $absentRows,
            'paid' => $paidRows,
            default => $presentRows->concat($absentRows)->concat($paidRows)->values(),
        };

        return view('crm.session', [
            'heroImage' => $this->heroImage(),
            'page' => config('crm.page', []),
            'sessionNo' => $session,
            'event' => $event,
            'search' => $search,
            'status' => $status,
            'team' => $team,
            'registered' => (int) $buckets['registered'],
            'present' => (int) $buckets['present'],
            'absent' => (int) $buckets['absent'],
            'paid' => (int) $buckets['paid'],
            'presentRows' => $presentRows,
            'absentRows' => $absentRows,
            'paidRows' => $paidRows,
            'listRows' => $listRows,
            'allowedSessions' => $allowed,
            'employees' => $employees,
            'assignments' => $assignments,
            'teamCounts' => $teamCounts,
            'unassignedCount' => $unassignedCount,
            'followupStatusOptions' => \App\Models\CrmFollowup::statusOptions(),
            'isAdmin' => true,
        ]);
    }

    public function assign(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_inquiry_id' => ['required', 'integer', 'exists:contact_inquiries,id'],
            'session_number' => ['required', 'integer'],
            'attendance_status' => ['required', 'in:present,absent'],
            'crm_employee_id' => ['required', 'integer', 'exists:crm_employees,id'],
        ]);

        $allowed = bns_intro_session_allowed_numbers();
        $sessionNo = (int) $validated['session_number'];
        if (! in_array($sessionNo, $allowed, true)) {
            return back()->withErrors(['crm_employee_id' => 'Invalid session.']);
        }

        $employee = CrmEmployee::query()->where('is_active', true)->find($validated['crm_employee_id']);
        if (! $employee) {
            return back()->withErrors(['crm_employee_id' => 'Select an active employee.']);
        }

        CrmAssignment::query()->updateOrCreate(
            [
                'contact_inquiry_id' => (int) $validated['contact_inquiry_id'],
                'session_number' => $sessionNo,
            ],
            [
                'crm_employee_id' => $employee->id,
                'attendance_status' => $validated['attendance_status'],
                'assigned_at' => now(),
            ]
        );

        $member = ContactInquiry::query()->find($validated['contact_inquiry_id']);
        $name = $member?->full_name ?: 'Member';

        return back()->with('status', $name.' assigned to '.$employee->name.'.');
    }

    public function assignBoard(Request $request): View
    {
        $employees = Schema::hasTable('crm_employees')
            ? CrmEmployee::query()
                ->where('is_active', true)
                ->withCount(['assignments as assignments_count' => function ($query) use ($request) {
                    $sessionFilter = (int) $request->query('session', 0);
                    if ($sessionFilter > 0) {
                        $query->where('session_number', $sessionFilter);
                    }
                }])
                ->orderBy('name')
                ->get()
            : collect();

        $employeeId = (int) $request->query('employee', 0);
        if ($employeeId < 1 && $employees->isNotEmpty()) {
            $employeeId = (int) $employees->first()->id;
        }

        $selectedEmployee = $employees->firstWhere('id', $employeeId);
        $sessionNo = (int) $request->query('session', 0);
        $scope = strtolower(trim((string) $request->query('scope', 'unassigned')));
        if (! in_array($scope, ['unassigned', 'mine', 'all'], true)) {
            $scope = 'unassigned';
        }
        $search = trim((string) $request->query('q', ''));

        $assignments = Schema::hasTable('crm_assignments')
            ? CrmAssignment::query()->with('employee')->get()
            : collect();
        $assignmentMap = $assignments->keyBy(fn (CrmAssignment $row) => $row->contact_inquiry_id.'-'.$row->session_number);

        $rows = $this->attendanceRows($sessionNo, $search)->map(function (object $hit) use ($assignmentMap) {
            $hit->assignment = $assignmentMap->get($hit->inquiry->id.'-'.$hit->session_number);
            $hit->is_confirmed = CrmLeadStatus::isConfirmed($hit->inquiry);

            return $hit;
        });

        $rows = $rows->filter(function (object $hit) use ($scope, $employeeId) {
            $assignedTo = (int) optional($hit->assignment)->crm_employee_id;
            if ($scope === 'unassigned') {
                return ! $hit->assignment && ! $hit->is_confirmed;
            }
            if ($scope === 'mine') {
                return $assignedTo === $employeeId;
            }

            return true;
        })->values();

        $sessionAssignments = $sessionNo > 0
            ? $assignments->where('session_number', $sessionNo)
            : $assignments;

        $unassignedCount = $this->attendanceRows($sessionNo, '')->filter(function (object $hit) use ($assignmentMap) {
            return ! $assignmentMap->has($hit->inquiry->id.'-'.$hit->session_number)
                && ! CrmLeadStatus::isConfirmed($hit->inquiry);
        })->count();

        return view('crm.assign', [
            'heroImage' => $this->heroImage(),
            'page' => config('crm.page', []),
            'employees' => $employees,
            'selectedEmployee' => $selectedEmployee,
            'employeeId' => $employeeId,
            'sessionNo' => $sessionNo,
            'scope' => $scope,
            'search' => $search,
            'rows' => $rows,
            'allowedSessions' => bns_intro_session_allowed_numbers(),
            'isAdmin' => true,
            'totals' => [
                'employees' => $employees->count(),
                'assigned' => $sessionAssignments->count(),
                'unassigned' => $unassignedCount,
                'mine' => $sessionAssignments->where('crm_employee_id', $employeeId)->count(),
            ],
        ]);
    }

    public function assignBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'crm_employee_id' => ['required', 'integer', 'exists:crm_employees,id'],
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['required', 'string'],
        ], [
            'selected.required' => 'Select at least one member.',
        ]);

        $employee = CrmEmployee::query()->where('is_active', true)->find($validated['crm_employee_id']);
        if (! $employee) {
            return back()->withErrors(['crm_employee_id' => 'Select an active employee.']);
        }

        $allowed = bns_intro_session_allowed_numbers();
        $count = 0;

        foreach ($validated['selected'] as $token) {
            $parts = explode('|', (string) $token);
            if (count($parts) !== 3) {
                continue;
            }

            $inquiryId = (int) $parts[0];
            $sessionNo = (int) $parts[1];
            $status = $parts[2] === 'absent' ? 'absent' : 'present';

            if ($inquiryId < 1 || ! in_array($sessionNo, $allowed, true)) {
                continue;
            }

            if (! ContactInquiry::query()->whereKey($inquiryId)->exists()) {
                continue;
            }

            CrmAssignment::query()->updateOrCreate(
                [
                    'contact_inquiry_id' => $inquiryId,
                    'session_number' => $sessionNo,
                ],
                [
                    'crm_employee_id' => $employee->id,
                    'attendance_status' => $status,
                    'assigned_at' => now(),
                ]
            );
            $count++;
        }

        return back()->with('status', $count.' '.Str::plural('member', $count).' assigned to '.$employee->name.'.');
    }

    public function unassign(CrmAssignment $assignment): RedirectResponse
    {
        $assignment->load('inquiry');
        $name = $assignment->inquiry?->full_name ?: 'Member';
        $assignment->delete();

        return back()->with('status', $name.' removed from this employee call list.');
    }

    public function allocate(CrmAllocationService $allocation): RedirectResponse
    {
        $employees = Schema::hasTable('crm_employees')
            ? CrmEmployee::query()->where('is_active', true)->count()
            : 0;

        if ($employees < 1) {
            return redirect()->route('crm.assign.board')->withErrors(['allocate' => 'Add an active employee first.']);
        }

        $count = $allocation->allocateUnassigned();
        $lastSession = $allocation->lastSessionNumber();

        return redirect()->route('crm.assign.board', array_filter([
            'session' => $lastSession > 0 ? $lastSession : null,
            'scope' => 'all',
        ]))->with(
            'status',
            $count > 0
                ? $count.' Session '.$lastSession.' member(s) divided evenly across all employees.'
                : 'No Session '.$lastSession.' registered members left to allocate.'
        );
    }

    /**
     * @return Collection<int, object>
     */
    private function attendanceRows(int $sessionFilter, string $search): Collection
    {
        $sessions = $sessionFilter > 0
            ? [$sessionFilter]
            : bns_intro_session_allowed_numbers();

        $hits = collect();

        foreach ($sessions as $sessionNo) {
            $breakdown = bns_session_attendance_breakdown($sessionNo);

            foreach (['present' => $breakdown['present_rows'], 'absent' => $breakdown['absent_rows']] as $status => $rows) {
                foreach ($this->filterRows($rows, $search) as $row) {
                    $hits->push((object) [
                        'session_number' => (int) $sessionNo,
                        'status' => $status,
                        'inquiry' => $row,
                    ]);
                }
            }
        }

        return $hits->values();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function sessionCards(): array
    {
        $cards = [];

        foreach (bns_intro_session_allowed_numbers() as $sessionNo) {
            $event = bns_introduction_session($sessionNo) ?? [];
            $buckets = $this->crmAttendanceBuckets(bns_session_attendance_breakdown($sessionNo));
            $registered = (int) $buckets['registered'];
            $present = (int) $buckets['present'];
            $absent = (int) $buckets['absent'];
            $paid = (int) $buckets['paid'];

            $cards[] = [
                'number' => $sessionNo,
                'title' => bns_intro_session_label($sessionNo, is_array($event) ? $event : null),
                'date' => (string) ($event['date'] ?? ''),
                'time' => (string) ($event['time'] ?? ''),
                'registered' => $registered,
                'present' => $present,
                'absent' => $absent,
                'paid' => $paid,
                'assigned' => Schema::hasTable('crm_assignments')
                    ? CrmAssignment::query()->where('session_number', $sessionNo)->count()
                    : 0,
                'present_pct' => $registered > 0 ? (int) round((($present + $paid) / $registered) * 100) : 0,
            ];
        }

        return $cards;
    }

    /**
     * @param  array<int, array<string, mixed>>  $sessions
     * @return Collection<int, object>
     */
    private function searchAcrossSessions(string $search, array $sessions): Collection
    {
        $hits = collect();

        foreach ($sessions as $session) {
            $sessionNo = (int) $session['number'];
            $buckets = $this->crmAttendanceBuckets(bns_session_attendance_breakdown($sessionNo));

            foreach ([
                'present' => $buckets['present_rows'],
                'absent' => $buckets['absent_rows'],
                'paid' => $buckets['paid_rows'],
            ] as $status => $rows) {
                foreach ($this->filterRows($rows, $search) as $row) {
                    $hits->push((object) [
                        'session_number' => $sessionNo,
                        'status' => $status,
                        'inquiry' => $row,
                    ]);
                }
            }
        }

        return $hits->values();
    }

    /**
     * @return Collection<int, object>
     */
    private function assignedHits(int $sessionFilter, string $search): Collection
    {
        if (! Schema::hasTable('crm_assignments')) {
            return collect();
        }

        $query = CrmAssignment::query()
            ->with('inquiry')
            ->orderByDesc('id');

        if ($sessionFilter > 0) {
            $query->where('session_number', $sessionFilter);
        }

        $hits = collect();
        foreach ($query->get() as $assignment) {
            $inquiry = $assignment->inquiry;
            if (! $inquiry instanceof ContactInquiry) {
                continue;
            }

            $hits->push((object) [
                'session_number' => (int) $assignment->session_number,
                'status' => $assignment->attendance_status === 'present' ? 'present' : 'absent',
                'inquiry' => $inquiry,
            ]);
        }

        if ($search === '') {
            return $hits->values();
        }

        $needle = mb_strtolower($search);

        return $hits
            ->filter(function (object $hit) use ($needle) {
                $row = $hit->inquiry;
                $hay = mb_strtolower(implode(' ', array_filter([
                    (string) $row->full_name,
                    (string) $row->email,
                    (string) $row->mobile,
                    (string) $row->registration_number,
                ])));

                return str_contains($hay, $needle);
            })
            ->values();
    }

    /**
     * Present / absent exclude paid members so: present + absent + payment done = total.
     *
     * @param  array{present_rows: Collection, absent_rows: Collection}  $breakdown
     * @return array{present_rows: Collection, absent_rows: Collection, paid_rows: Collection, present: int, absent: int, paid: int, registered: int}
     */
    private function crmAttendanceBuckets(array $breakdown): array
    {
        $paidIds = array_fill_keys(CrmLeadStatus::paidInquiryIds(), true);
        $isPaid = static fn (ContactInquiry $row): bool => isset($paidIds[(int) $row->id]);

        $presentRows = collect($breakdown['present_rows'] ?? [])->filter(fn (ContactInquiry $row) => ! $isPaid($row))->values();
        $absentRows = collect($breakdown['absent_rows'] ?? [])->filter(fn (ContactInquiry $row) => ! $isPaid($row))->values();
        $paidRows = collect($breakdown['present_rows'] ?? [])
            ->concat(collect($breakdown['absent_rows'] ?? []))
            ->filter($isPaid)
            ->unique('id')
            ->values();

        $present = $presentRows->count();
        $absent = $absentRows->count();
        $paid = $paidRows->count();

        return [
            'present_rows' => $presentRows,
            'absent_rows' => $absentRows,
            'paid_rows' => $paidRows,
            'present' => $present,
            'absent' => $absent,
            'paid' => $paid,
            'registered' => $present + $absent + $paid,
        ];
    }

    /**
     * @param  Collection<int, ContactInquiry>  $rows
     * @return Collection<int, ContactInquiry>
     */
    private function filterRows(Collection $rows, string $search): Collection
    {
        $search = trim($search);
        if ($search === '') {
            return $rows->values();
        }

        $needle = mb_strtolower($search);

        return $rows
            ->filter(function (ContactInquiry $row) use ($needle) {
                $hay = mb_strtolower(implode(' ', array_filter([
                    (string) $row->full_name,
                    (string) $row->email,
                    (string) $row->mobile,
                    (string) $row->whatsapp,
                    (string) $row->registration_number,
                    (string) $row->city,
                    (string) $row->state,
                    (string) $row->interested_program,
                    (string) $row->organization_name,
                ])));

                return str_contains($hay, $needle);
            })
            ->values();
    }

    private function todayAttendanceCount(): int
    {
        if (! Schema::hasTable('session_attendances')) {
            return 0;
        }

        return SessionAttendance::query()
            ->whereDate('attended_at', today())
            ->count();
    }

    /**
     * @return Collection<int, Collection<int, SessionAttendance>>
     */
    private function todayAttendanceGroups(string $search = '', ?Carbon $day = null): Collection
    {
        if (! Schema::hasTable('session_attendances')) {
            return collect();
        }

        $day = $day ?? now('Asia/Kolkata')->startOfDay();

        $query = SessionAttendance::query()
            ->with('inquiry')
            ->whereBetween('attended_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
            ->orderBy('session_number')
            ->orderBy('attended_at');

        $search = trim($search);
        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('registration_number', 'like', '%'.$search.'%')
                    ->orWhere('full_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('mobile', 'like', '%'.$search.'%')
                    ->orWhere('program', 'like', '%'.$search.'%');
            });
        }

        return $query->get()->groupBy(fn (SessionAttendance $row) => (int) $row->session_number);
    }

    /**
     * @return Collection<int, CrmSpotAdmission>
     */
    private function todaySpotAdmissions(string $search = '', ?Carbon $day = null): Collection
    {
        if (! Schema::hasTable('crm_spot_admissions')) {
            return collect();
        }

        $day = $day ?? now('Asia/Kolkata')->startOfDay();

        $query = CrmSpotAdmission::query()
            ->whereBetween('attended_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
            ->orderBy('attended_at');

        $search = trim($search);
        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('full_name', 'like', '%'.$search.'%')
                    ->orWhere('mobile', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('register_no', 'like', '%'.$search.'%')
                    ->orWhere('facility_name', 'like', '%'.$search.'%');
            });
        }

        return $query->get();
    }

    private function attendanceDay(Request $request): Carbon
    {
        $tz = 'Asia/Kolkata';
        $raw = trim((string) $request->query('date', ''));
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw) === 1) {
            try {
                $day = Carbon::createFromFormat('Y-m-d', $raw, $tz);
                if ($day instanceof Carbon) {
                    return $day->startOfDay();
                }
            } catch (\Throwable) {
            }
        }

        return now($tz)->startOfDay();
    }

    private function heroImage(): string
    {
        return $this->homeImages->url('about_bg');
    }

    private function passwordMatches(string $expected, string $provided): bool
    {
        if ($expected === '') {
            return false;
        }

        if (str_starts_with($expected, '$2y$') || str_starts_with($expected, '$2a$') || str_starts_with($expected, '$argon')) {
            return Hash::check($provided, $expected);
        }

        return hash_equals($expected, $provided);
    }
}
