<?php

namespace App\Http\Controllers;

use App\Models\CrmAssignment;
use App\Models\CrmFollowup;
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
    public function __construct(private HomeImageService $homeImages) {}

    public function index(Request $request): View|RedirectResponse
    {
        if (CrmPortal::isAdmin($request)) {
            return redirect()->route('crm.dashboard');
        }

        $employee = CrmPortal::employee($request);
        if (! $employee) {
            abort(403);
        }

        $search = trim((string) $request->query('q', ''));
        $status = strtolower(trim((string) $request->query('status', 'all')));
        if (! in_array($status, ['all', 'present', 'absent'], true)) {
            $status = 'all';
        }
        $allowed = bns_intro_session_allowed_numbers();
        $sessionFilter = (int) $request->query('session', 0);
        if (! in_array($sessionFilter, $allowed, true)) {
            $sessionFilter = 0;
        }

        $query = CrmAssignment::query()
            ->with(['inquiry', 'followups', 'employee'])
            ->where('crm_employee_id', $employee->id)
            ->latest('assigned_at');

        if ($sessionFilter > 0) {
            $query->where('session_number', $sessionFilter);
        }

        CrmLeadStatus::excludeConfirmed($query);

        $allAssigned = (clone $query)->get();
        $paidCount = 0;
        if (Schema::hasTable('admission_payments')) {
            $paidCount = CrmLeadStatus::filterPaymentsForEmployee(
                CrmLeadStatus::successfulPaymentsQuery(),
                (int) $employee->id
            )->count();
        }

        $totals = [
            'assigned' => $allAssigned->count(),
            'present' => $allAssigned->where('attendance_status', 'present')->count(),
            'absent' => $allAssigned->where('attendance_status', 'absent')->count(),
            'followups_done' => $allAssigned->sum(fn (CrmAssignment $row) => $row->completedFollowups()),
            'paid' => $paidCount,
        ];

        if ($status !== 'all') {
            $query->where('attendance_status', $status);
        }

        $assignments = $query->get();
        if ($search !== '') {
            $needle = mb_strtolower($search);
            $assignments = $assignments->filter(function (CrmAssignment $assignment) use ($needle) {
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

        return view('crm.desk', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'employee' => $employee,
            'assignments' => $assignments,
            'search' => $search,
            'status' => $status,
            'sessionFilter' => $sessionFilter,
            'allowedSessions' => $allowed,
            'isAdmin' => false,
            'totals' => $totals,
        ]);
    }

    public function show(Request $request, CrmAssignment $assignment): View|RedirectResponse
    {
        $this->assertOwns($request, $assignment);
        $assignment->load(['inquiry', 'followups', 'employee']);

        if (! CrmPortal::isAdmin($request) && CrmLeadStatus::isConfirmed($assignment->inquiry)) {
            return redirect()
                ->route('crm.desk')
                ->with('status', ($assignment->inquiry->full_name ?? 'This member').' already paid, so they are not in the call list.');
        }

        $followups = [];
        for ($n = 1; $n <= 3; $n++) {
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

        if ($followup < 1 || $followup > 3) {
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
                    ->route('crm.desk')
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
}
