<?php

namespace App\Http\Controllers;

use App\Models\AdmissionPayment;
use App\Models\ContactInquiry;
use App\Models\CrmAssignment;
use App\Services\HomeImageService;
use App\Support\CrmLeadStatus;
use App\Support\CrmPortal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CrmConversionController extends Controller
{
    public function __construct(private HomeImageService $homeImages) {}

    public function payments(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $employee = CrmPortal::employee($request);
        $isAdmin = CrmPortal::isAdmin($request);
        $allowed = bns_intro_session_allowed_numbers();
        $sessionFilter = (int) $request->query('session', 0);
        if (! in_array($sessionFilter, $allowed, true)) {
            $sessionFilter = 0;
        }

        $payments = collect();
        if (Schema::hasTable('admission_payments')) {
            $query = CrmLeadStatus::successfulPaymentsQuery();
            if (! $isAdmin && $employee) {
                $query = CrmLeadStatus::filterPaymentsForEmployee($query, (int) $employee->id);
            }

            CrmLeadStatus::applySearch($query, $search, [
                'customer_name',
                'customer_mobile',
                'customer_email',
                'registration_number',
                'merchant_txn_no',
            ]);

            $mobileMap = bns_reporting_session_mobile_map(onlyIntro: false);

            $payments = $query->limit(300)->get()->map(function ($payment) use ($mobileMap, $allowed) {
                $inquiry = CrmLeadStatus::findInquiryForPayment($payment);
                $payment->matched_inquiry = $inquiry;
                $payment->is_confirmed = CrmLeadStatus::isConfirmed($inquiry);
                $payment->session_number = $this->sessionNumberForPayment($payment, $inquiry, $mobileMap, $allowed);

                return $payment;
            });
        }

        $allCount = $payments->count();
        $sessionTotals = [];
        foreach ($allowed as $sessionNo) {
            $sessionTotals[$sessionNo] = $payments->where('session_number', $sessionNo)->count();
        }

        $list = $sessionFilter > 0
            ? $payments->where('session_number', $sessionFilter)->values()
            : $payments->values();

        $groups = $list->groupBy(fn ($payment) => (int) ($payment->session_number ?: 0));

        return view('crm.payments', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'payments' => $list,
            'groups' => $groups,
            'allCount' => $allCount,
            'sessionTotals' => $sessionTotals,
            'allowedSessions' => $allowed,
            'sessionFilter' => $sessionFilter,
            'search' => $search,
            'isAdmin' => $isAdmin,
            'employee' => $employee,
        ]);
    }

    public function attendanceSync(Request $request): View
    {
        return view('crm.attendance-sync', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'isAdmin' => CrmPortal::isAdmin($request),
            'employee' => CrmPortal::employee($request),
            'syncUrl' => url('/api/attendance-sync/sync'),
            'appApiBase' => url('/api/attendance-sync'),
        ]);
    }

    public function admissions(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $employee = CrmPortal::employee($request);
        $isAdmin = CrmPortal::isAdmin($request);

        $query = CrmLeadStatus::confirmedInquiriesQuery();
        if (! $isAdmin && $employee) {
            $assignedIds = CrmAssignment::query()
                ->where('crm_employee_id', $employee->id)
                ->pluck('contact_inquiry_id')
                ->all();
            $query->whereIn('id', $assignedIds ?: [0]);
        }

        CrmLeadStatus::applySearch($query, $search, [
            'full_name',
            'mobile',
            'email',
            'registration_number',
            'city',
        ]);

        $admissions = $query->limit(300)->get()->map(function (ContactInquiry $inquiry) {
            $inquiry->has_payment = CrmLeadStatus::inquiryHasSuccessfulPayment($inquiry);

            return $inquiry;
        });

        return view('crm.admissions', [
            'heroImage' => $this->homeImages->url('about_bg'),
            'page' => config('crm.page', []),
            'admissions' => $admissions,
            'search' => $search,
            'isAdmin' => $isAdmin,
            'employee' => $employee,
        ]);
    }

    public function confirm(Request $request, ContactInquiry $inquiry): RedirectResponse
    {
        $this->assertCanConfirm($request, $inquiry);

        CrmLeadStatus::confirm($inquiry);

        return back()->with('status', ($inquiry->full_name ?: 'Member').' admission confirmed. They are now hidden from the call list.');
    }

    private function assertCanConfirm(Request $request, ContactInquiry $inquiry): void
    {
        if (CrmPortal::isAdmin($request)) {
            return;
        }

        $employee = CrmPortal::employee($request);
        if (! $employee) {
            abort(403);
        }

        $owns = CrmAssignment::query()
            ->where('crm_employee_id', $employee->id)
            ->where('contact_inquiry_id', $inquiry->id)
            ->exists();

        if (! $owns) {
            abort(403, 'This member is not assigned to you.');
        }
    }

    /**
     * @param  array<string, array{session: int, source: string, id: int}>  $mobileMap
     * @param  list<int>  $allowed
     */
    private function sessionNumberForPayment(AdmissionPayment $payment, ?ContactInquiry $inquiry, array $mobileMap, array $allowed): int
    {
        if ($inquiry) {
            $stored = (int) ($inquiry->intro_session_number ?? 0);
            if (in_array($stored, $allowed, true)) {
                return $stored;
            }
        }

        $mobile = ContactInquiry::normalizeMobile(
            ($inquiry?->mobile ?: $payment->customer_mobile) ?? ''
        );
        if ($mobile === '') {
            return 0;
        }

        $row = $mobileMap[$mobile] ?? null;
        $session = is_array($row) ? (int) ($row['session'] ?? 0) : 0;

        return in_array($session, $allowed, true) ? $session : 0;
    }
}
