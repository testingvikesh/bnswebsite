<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPayment;
use App\Models\ContactInquiry;
use App\Support\CrmLeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IntroSessionRegisterController extends Controller
{
    public function index(Request $request): View
    {
        $state = $this->stateFromRequest($request);
        $rows = $this->rowsForView($state);

        return view('sop.intro-session-registers.index', [
            ...$state,
            'rows' => $rows,
            'sessions' => bns_introduction_sessions(),
            'stats' => $this->stats($state),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $state = $this->stateFromRequest($request);
        $rows = $this->rowsForView($state);
        $isPaid = $state['view'] === 'paid';
        $sessionLabel = $state['session'] > 0 ? 'session-'.$state['session'] : 'all-sessions';
        $filename = 'bns-intro-'.$state['view'].'-'.$sessionLabel.'-'.now()->format('Ymd-His').'.xls';

        $headers = $isPaid
            ? ['Sr. No.', 'Session', 'Paid Date', 'Name', 'Mobile', 'Email', 'Reg. No.', 'Amount', 'Payment Mode', 'Txn No.', 'Program']
            : ['Sr. No.', 'Session', 'Registered At', 'Name', 'Mobile', 'Email', 'Reg. No.', 'Form Source', 'Program', 'Payment', 'Amount', 'Paid Date'];

        return response()->streamDownload(function () use ($rows, $headers, $isPaid) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            $sr = 0;
            foreach ($rows as $row) {
                $sr++;
                $inquiry = $row['inquiry'];
                $payment = $row['payment'];
                $sessionNo = (int) $row['session'];

                if ($isPaid) {
                    fputcsv($handle, [
                        $sr,
                        $sessionNo > 0 ? 'Session '.$sessionNo : '',
                        $payment?->paid_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?? '',
                        $payment?->customer_name ?: ($inquiry->full_name ?? ''),
                        $payment?->customer_mobile ?: ($inquiry->mobile ?? ''),
                        $payment?->customer_email ?: ($inquiry->email ?? ''),
                        $payment?->registration_number ?: ($inquiry->registration_number ?? ''),
                        $payment ? number_format((float) $payment->amount, 2, '.', '') : '',
                        $payment?->payment_mode ?? '',
                        $payment?->merchant_txn_no ?? '',
                        $inquiry->interested_program ?? '',
                    ]);
                    continue;
                }

                fputcsv($handle, [
                    $sr,
                    $sessionNo > 0 ? 'Session '.$sessionNo : '',
                    $inquiry?->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?? '',
                    $inquiry->full_name ?? '',
                    $inquiry->mobile ?? '',
                    $inquiry->email ?? '',
                    $inquiry->registration_number ?? '',
                    $inquiry?->formSourceLabel() ?? '',
                    $inquiry->interested_program ?? '',
                    $payment ? 'Payment done' : 'Not paid',
                    $payment ? number_format((float) $payment->amount, 2, '.', '') : '',
                    $payment?->paid_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    /**
     * @return array{view: string, session: int, search: string, allowed: list<int>}
     */
    private function stateFromRequest(Request $request): array
    {
        $allowed = bns_intro_session_allowed_numbers();
        $view = strtolower(trim((string) $request->query('view', 'registered')));
        if (! in_array($view, ['registered', 'paid'], true)) {
            $view = 'registered';
        }

        $session = (int) $request->query('session', 0);
        if ($session !== 0 && ! in_array($session, $allowed, true)) {
            $session = 0;
        }

        return [
            'view' => $view,
            'session' => $session,
            'search' => trim((string) $request->query('q', '')),
            'allowed' => $allowed,
        ];
    }

    /**
     * @param  array{view: string, session: int, search: string, allowed: list<int>}  $state
     * @return array{registered: int, paid: int, filtered: int, session_totals: array<int, int>, session_paid: array<int, int>}
     */
    private function stats(array $state): array
    {
        $registeredRows = $this->registeredRows(0, '');
        $paidRows = $this->paidRows(0, '');
        $filtered = $this->rowsForView($state);

        $sessionTotals = [];
        $sessionPaid = [];
        foreach ($state['allowed'] as $sessionNo) {
            $sessionTotals[$sessionNo] = $registeredRows->where('session', $sessionNo)->count();
            $sessionPaid[$sessionNo] = $paidRows->where('session', $sessionNo)->count();
        }

        return [
            'registered' => $registeredRows->count(),
            'paid' => $paidRows->count(),
            'filtered' => $filtered->count(),
            'session_totals' => $sessionTotals,
            'session_paid' => $sessionPaid,
        ];
    }

    /**
     * @param  array{view: string, session: int, search: string, allowed: list<int>}  $state
     * @return Collection<int, array{session: int, inquiry: ?ContactInquiry, payment: ?AdmissionPayment}>
     */
    private function rowsForView(array $state): Collection
    {
        return $state['view'] === 'paid'
            ? $this->paidRows($state['session'], $state['search'])
            : $this->registeredRows($state['session'], $state['search']);
    }

    /**
     * @return Collection<int, array{session: int, inquiry: ContactInquiry, payment: ?AdmissionPayment}>
     */
    private function registeredRows(int $sessionFilter, string $search): Collection
    {
        $map = bns_reporting_session_mobile_map(onlyIntro: false);
        $lookup = $this->paymentLookup();
        $idsBySession = [];

        foreach ($map as $row) {
            $id = (int) ($row['id'] ?? 0);
            $session = (int) ($row['session'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $idsBySession[$id] = $session;
        }

        if ($idsBySession === []) {
            return collect();
        }

        $query = ContactInquiry::query()
            ->whereIn('id', array_keys($idsBySession))
            ->orderByDesc('id');

        $this->applyInquirySearch($query, $search);

        return $query->get()
            ->map(function (ContactInquiry $inquiry) use ($idsBySession, $lookup) {
                $session = (int) ($idsBySession[(int) $inquiry->id] ?? 0);

                return [
                    'session' => $session,
                    'inquiry' => $inquiry,
                    'payment' => $this->paymentForInquiry($inquiry, $lookup),
                ];
            })
            ->when($sessionFilter > 0, fn (Collection $rows) => $rows->where('session', $sessionFilter)->values())
            ->values();
    }

    /**
     * @return Collection<int, array{session: int, inquiry: ?ContactInquiry, payment: AdmissionPayment}>
     */
    private function paidRows(int $sessionFilter, string $search): Collection
    {
        if (! Schema::hasTable('admission_payments')) {
            return collect();
        }

        $query = CrmLeadStatus::successfulPaymentsQuery();
        CrmLeadStatus::applySearch($query, $search, [
            'customer_name',
            'customer_mobile',
            'customer_email',
            'registration_number',
            'merchant_txn_no',
        ]);

        $allowed = bns_intro_session_allowed_numbers();
        $map = bns_reporting_session_mobile_map(onlyIntro: false);

        return $query->get()
            ->map(function (AdmissionPayment $payment) use ($map, $allowed) {
                $inquiry = CrmLeadStatus::findInquiryForPayment($payment);

                return [
                    'session' => $this->sessionNumberForPayment($payment, $inquiry, $map, $allowed),
                    'inquiry' => $inquiry,
                    'payment' => $payment,
                ];
            })
            ->when($sessionFilter > 0, fn (Collection $rows) => $rows->where('session', $sessionFilter)->values())
            ->values();
    }

    /**
     * @return array{byInquiry: array<int, AdmissionPayment>, byReg: array<string, AdmissionPayment>, byMobile: array<string, AdmissionPayment>}
     */
    private function paymentLookup(): array
    {
        $byInquiry = [];
        $byReg = [];
        $byMobile = [];

        if (! Schema::hasTable('admission_payments')) {
            return compact('byInquiry', 'byReg', 'byMobile');
        }

        $payments = AdmissionPayment::query()
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->get();

        foreach ($payments as $payment) {
            if ($payment->payable_type === ContactInquiry::class && $payment->payable_id) {
                $byInquiry[(int) $payment->payable_id] ??= $payment;
            }

            $reg = trim((string) $payment->registration_number);
            if ($reg !== '') {
                $byReg[$reg] ??= $payment;
            }

            $mobile = ContactInquiry::normalizeMobile((string) $payment->customer_mobile);
            if ($mobile !== '') {
                $byMobile[$mobile] ??= $payment;
            }
        }

        return compact('byInquiry', 'byReg', 'byMobile');
    }

    /**
     * @param  array{byInquiry: array<int, AdmissionPayment>, byReg: array<string, AdmissionPayment>, byMobile: array<string, AdmissionPayment>}  $lookup
     */
    private function paymentForInquiry(ContactInquiry $inquiry, array $lookup): ?AdmissionPayment
    {
        $id = (int) $inquiry->id;
        if (isset($lookup['byInquiry'][$id])) {
            return $lookup['byInquiry'][$id];
        }

        $reg = trim((string) $inquiry->registration_number);
        if ($reg !== '' && isset($lookup['byReg'][$reg])) {
            return $lookup['byReg'][$reg];
        }

        $mobile = ContactInquiry::normalizeMobile((string) $inquiry->mobile);
        if ($mobile !== '' && isset($lookup['byMobile'][$mobile])) {
            return $lookup['byMobile'][$mobile];
        }

        return null;
    }

    /**
     * @param  array<string, array{session: int, source: string, id: int}>  $mobileMap
     * @param  list<int>  $allowed
     */
    private function sessionNumberForPayment(
        AdmissionPayment $payment,
        ?ContactInquiry $inquiry,
        array $mobileMap,
        array $allowed
    ): int {
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

    private function applyInquirySearch($query, string $search): void
    {
        $search = trim($search);
        if ($search === '') {
            return;
        }

        $query->where(function ($builder) use ($search) {
            $builder
                ->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->orWhere('registration_number', 'like', "%{$search}%")
                ->orWhere('interested_program', 'like', "%{$search}%");
        });
    }
}
