<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPayment;
use App\Models\ContactInquiry;
use App\Support\ColoredXlsx;
use App\Support\CrmLeadStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

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

    public function export(Request $request): Response
    {
        $state = $this->stateFromRequest($request);
        $rows = $this->rowsForView($state);
        $isPaid = $state['view'] === 'paid';
        $sessionLabel = $state['session'] > 0 ? 'session-'.$state['session'] : 'all-sessions';
        $filename = 'bns-intro-'.$state['view'].'-'.$sessionLabel.'-'.now()->format('Ymd-His').'.xlsx';
        $title = $isPaid ? 'Payment Done Users' : 'Intro Session Registered Users';
        $sessionTitle = $state['session'] > 0
            ? bns_intro_session_label($state['session'])
            : 'All Sessions';
        $event = $state['session'] > 0 ? bns_introduction_session($state['session']) : null;
        $stats = $this->stats($state);
        $xlsx = new ColoredXlsx();
        $colCount = $isPaid ? 11 : 9;
        $lastCol = $isPaid ? 'K' : 'I';
        $c = fn (string $value, int $style = 0) => $xlsx->cell($value, $style);

        $pad = function (array $cells) use ($c, $colCount): array {
            while (count($cells) < $colCount) {
                $cells[] = $c('');
            }

            return $cells;
        };

        $meta = 'Generated: '.now('Asia/Kolkata')->format('d M Y, h:i A').' (IST)';
        if (is_array($event)) {
            $meta .= ' · '.trim((string) (($event['date'] ?? '').' '.($event['time'] ?? '')));
        }
        if ($state['search'] !== '') {
            $meta .= ' · Search: '.$state['search'];
        }

        $sheet = [
            $pad([$c('Business Navachar School (BNS)', ColoredXlsx::S_BRAND)]),
            $pad([$c($title.' · '.$sessionTitle, ColoredXlsx::S_SUBTITLE)]),
            $pad([$c($meta, ColoredXlsx::S_META)]),
            $pad($isPaid
                ? [
                    $c('Registered', ColoredXlsx::S_STAT_LABEL),
                    $c('Payment Done', ColoredXlsx::S_STAT_LABEL),
                    $c('Showing', ColoredXlsx::S_STAT_LABEL),
                    $c('Report', ColoredXlsx::S_STAT_LABEL),
                ]
                : [
                    $c('Registered', ColoredXlsx::S_STAT_LABEL),
                    $c('Showing', ColoredXlsx::S_STAT_LABEL),
                    $c('Report', ColoredXlsx::S_STAT_LABEL),
                ]),
            $pad($isPaid
                ? [
                    $c((string) number_format($stats['registered'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                    $c((string) number_format($stats['paid'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                    $c((string) number_format($stats['filtered'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                    $c('Successful payment list', ColoredXlsx::S_META),
                ]
                : [
                    $c((string) number_format($stats['registered'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                    $c((string) number_format($stats['filtered'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                    $c('Unique registered members', ColoredXlsx::S_META),
                ]),
        ];

        $headers = $isPaid
            ? ['Sr. No.', 'Session', 'Paid Date', 'Name', 'Mobile', 'Email', 'Reg. No.', 'Amount', 'Payment Mode', 'Txn No.', 'Program']
            : ['Sr. No.', 'Session', 'Registered At', 'Name', 'Mobile', 'Email', 'Reg. No.', 'Form Source', 'Program'];
        $sheet[] = array_map(fn (string $label) => $c($label, ColoredXlsx::S_HEAD), $headers);

        foreach ($rows as $index => $row) {
            $inquiry = $row['inquiry'] ?? null;
            $payment = $row['payment'] ?? null;
            $sessionNo = (int) ($row['session'] ?? 0);
            $rowStyle = ColoredXlsx::S_ROW_PAID;
            $textStyle = ColoredXlsx::S_TEXT_PAID;
            $amountStyle = ColoredXlsx::S_AMOUNT;
            if (! $isPaid) {
                $rowStyle = $index % 2 === 0 ? ColoredXlsx::S_ROW_UNPAID : ColoredXlsx::S_META;
                $textStyle = ColoredXlsx::S_TEXT_UNPAID;
            }

            if ($isPaid) {
                $sheet[] = [
                    $c((string) ($index + 1), $rowStyle),
                    $c($sessionNo > 0 ? bns_intro_session_label($sessionNo) : '—', ColoredXlsx::S_SESSION),
                    $c($payment?->paid_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—', $rowStyle),
                    $c((string) ($payment->customer_name ?? ($inquiry?->full_name ?? '—')), $rowStyle),
                    $c((string) ($payment->customer_mobile ?? ($inquiry?->mobile ?? '—')), $textStyle),
                    $c((string) ($payment->customer_email ?? ($inquiry?->email ?? '—')), $rowStyle),
                    $c((string) ($payment->registration_number ?? ($inquiry?->registration_number ?? '—')), $textStyle),
                    $c($payment ? number_format((float) $payment->amount, 2) : '', $amountStyle),
                    $c((string) ($payment->payment_mode ?? '—'), $rowStyle),
                    $c((string) ($payment->merchant_txn_no ?? '—'), $textStyle),
                    $c((string) ($inquiry?->interested_program ?? '—'), $rowStyle),
                ];
                continue;
            }

            $sheet[] = [
                $c((string) ($index + 1), $rowStyle),
                $c($sessionNo > 0 ? bns_intro_session_label($sessionNo) : '—', ColoredXlsx::S_SESSION),
                $c($inquiry?->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—', $rowStyle),
                $c((string) ($inquiry->full_name ?? '—'), $rowStyle),
                $c((string) ($inquiry->mobile ?? '—'), $textStyle),
                $c((string) ($inquiry->email ?? '—'), $rowStyle),
                $c((string) ($inquiry->registration_number ?? '—'), $textStyle),
                $c((string) ($inquiry?->formSourceLabel() ?? '—'), $rowStyle),
                $c((string) ($inquiry->interested_program ?? '—'), $rowStyle),
            ];
        }

        if ($rows->isEmpty()) {
            $sheet[] = $pad([$c('No records found.', ColoredXlsx::S_META)]);
        }

        $binary = $xlsx->build(
            $isPaid ? 'Payment Done' : 'Registered Users',
            $sheet,
            $isPaid
                ? ['A1:'.$lastCol.'1', 'A2:'.$lastCol.'2', 'A3:'.$lastCol.'3', 'D4:'.$lastCol.'4', 'D5:'.$lastCol.'5']
                : ['A1:'.$lastCol.'1', 'A2:'.$lastCol.'2', 'A3:'.$lastCol.'3', 'C4:'.$lastCol.'4', 'C5:'.$lastCol.'5'],
            $isPaid
                ? [1 => 8, 2 => 22, 3 => 22, 4 => 28, 5 => 16, 6 => 28, 7 => 20, 8 => 12, 9 => 16, 10 => 22, 11 => 28]
                : [1 => 8, 2 => 22, 3 => 22, 4 => 28, 5 => 16, 6 => 28, 7 => 20, 8 => 22, 9 => 28]
        );

        return response($binary, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate',
            'Pragma' => 'public',
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
