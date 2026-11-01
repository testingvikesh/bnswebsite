<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AdmissionPayment;
use App\Models\ContactInquiry;
use App\Models\SessionAttendance;
use App\Support\ColoredXlsx;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class IntroSessionRegisterController extends Controller
{
    /** @var Collection<int, array{session: int, inquiry: ContactInquiry, payment: ?AdmissionPayment, present: bool}>|null */
    private ?Collection $allRows = null;

    public function index(Request $request): View
    {
        $state = $this->stateFromRequest($request);
        $rows = $this->rowsForView($state);

        return view('sop.intro-session-registers.index', [
            ...$state,
            'rows' => $rows,
            'sessions' => bns_introduction_sessions(),
            'stats' => $this->stats($state),
            'programOptions' => config('contact.form.interested_programs', []),
            'formSourceOptions' => config('reporting.form_sources', []),
        ]);
    }

    public function export(Request $request): Response
    {
        $state = $this->stateFromRequest($request);
        $rows = $this->rowsForView($state);
        $viewLabel = $this->viewLabel($state['view']);
        $sessionLabel = $state['session'] > 0 ? 'session-'.$state['session'] : 'all-sessions';
        $filename = 'bns-intro-'.str_replace('_', '-', $state['view']).'-'.$sessionLabel.'-'.now()->format('Ymd-His').'.xlsx';
        $sessionTitle = $state['session'] > 0
            ? bns_intro_session_label($state['session'])
            : 'All Sessions';
        $event = $state['session'] > 0 ? bns_introduction_session($state['session']) : null;
        $stats = $this->stats($state);
        $xlsx = new ColoredXlsx();
        $colCount = 14;
        $lastCol = 'N';
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
        if (($state['payment'] ?? '') !== '') {
            $meta .= ' · Payment: '.(($state['payment'] ?? '') === 'paid' ? 'Payment done' : 'Not paid');
        }
        if (($state['program'] ?? '') !== '') {
            $meta .= ' · Program: '.$state['program'];
        }
        if (($state['source'] ?? '') !== '') {
            $meta .= ' · Source: '.(config('reporting.form_sources.'.$state['source']) ?: $state['source']);
        }
        if (($state['date_from'] ?? '') !== '' || ($state['date_to'] ?? '') !== '') {
            $meta .= ' · Date: '.trim(($state['date_from'] ?? '').' to '.($state['date_to'] ?? ''));
        }

        $sheet = [
            $pad([$c('Business Navachar School (BNS)', ColoredXlsx::S_BRAND)]),
            $pad([$c($viewLabel.' · '.$sessionTitle, ColoredXlsx::S_SUBTITLE)]),
            $pad([$c($meta, ColoredXlsx::S_META)]),
            $pad([
                $c('Total', ColoredXlsx::S_STAT_LABEL),
                $c('Payment Done', ColoredXlsx::S_STAT_LABEL),
                $c('Remain', ColoredXlsx::S_STAT_LABEL),
                $c('Remain Present', ColoredXlsx::S_STAT_LABEL),
                $c('Remain Absent', ColoredXlsx::S_STAT_LABEL),
                $c('This list', ColoredXlsx::S_STAT_LABEL),
            ]),
            $pad([
                $c((string) number_format($stats['total'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                $c((string) number_format($stats['paid'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                $c((string) number_format($stats['remain'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                $c((string) number_format($stats['remain_present'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                $c((string) number_format($stats['remain_absent'] ?? 0), ColoredXlsx::S_STAT_VALUE),
                $c((string) number_format($rows->count()).' · '.$viewLabel, ColoredXlsx::S_META),
            ]),
        ];

        $headers = ['Sr. No.', 'Session', 'Name', 'Mobile', 'Email', 'Reg. No.', 'Program', 'Registered At', 'Payment', 'Amount', 'Paid At', 'Txn No.', 'Attendance', 'Form Source'];
        $sheet[] = array_map(fn (string $label) => $c($label, ColoredXlsx::S_HEAD), $headers);

        foreach ($rows as $index => $row) {
            $inquiry = $row['inquiry'] ?? null;
            $payment = $row['payment'] ?? null;
            $sessionNo = (int) ($row['session'] ?? 0);
            $isPaidRow = $payment !== null;
            $rowStyle = $isPaidRow
                ? ColoredXlsx::S_ROW_PAID
                : ($index % 2 === 0 ? ColoredXlsx::S_ROW_UNPAID : ColoredXlsx::S_META);
            $textStyle = $isPaidRow ? ColoredXlsx::S_TEXT_PAID : ColoredXlsx::S_TEXT_UNPAID;
            $name = (string) ($payment->customer_name ?? ($inquiry->full_name ?? '—'));
            $mobile = (string) ($payment->customer_mobile ?? ($inquiry->mobile ?? '—'));
            $email = (string) ($payment->customer_email ?? ($inquiry->email ?? '—'));
            $reg = (string) ($payment->registration_number ?? ($inquiry->registration_number ?? '—'));

            $sheet[] = [
                $c((string) ($index + 1), $rowStyle),
                $c($sessionNo > 0 ? bns_intro_session_label($sessionNo) : '—', ColoredXlsx::S_SESSION),
                $c($name !== '' ? $name : '—', $rowStyle),
                $c($mobile !== '' ? $mobile : '—', $textStyle),
                $c($email !== '' ? $email : '—', $rowStyle),
                $c($reg !== '' ? $reg : '—', $textStyle),
                $c((string) ($inquiry?->interested_program ?? '—'), $rowStyle),
                $c($inquiry?->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—', $rowStyle),
                $c($isPaidRow ? 'Payment done' : 'Not paid', $isPaidRow ? ColoredXlsx::S_PAID : ColoredXlsx::S_UNPAID),
                $c($payment ? number_format((float) $payment->amount, 2) : '', ColoredXlsx::S_AMOUNT),
                $c($payment?->paid_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—', $rowStyle),
                $c((string) ($payment->merchant_txn_no ?? '—'), $textStyle),
                $c(! empty($row['present']) ? 'Present' : 'Absent', $rowStyle),
                $c((string) ($inquiry?->formSourceLabel() ?? '—'), $rowStyle),
            ];
        }

        if ($rows->isEmpty()) {
            $sheet[] = $pad([$c('No records found.', ColoredXlsx::S_META)]);
        }

        $binary = $xlsx->build(
            $viewLabel,
            $sheet,
            [
                'A1:'.$lastCol.'1',
                'A2:'.$lastCol.'2',
                'A3:'.$lastCol.'3',
                'F4:'.$lastCol.'4',
                'F5:'.$lastCol.'5',
            ],
            [1 => 8, 2 => 22, 3 => 28, 4 => 16, 5 => 28, 6 => 20, 7 => 24, 8 => 20, 9 => 16, 10 => 12, 11 => 20, 12 => 22, 13 => 14, 14 => 22]
        );

        return response($binary, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    /**
     * @return array{
     *     view: string,
     *     session: int,
     *     search: string,
     *     payment: string,
     *     program: string,
     *     source: string,
     *     date_from: string,
     *     date_to: string,
     *     allowed: list<int>
     * }
     */
    private function stateFromRequest(Request $request): array
    {
        $allowed = bns_intro_session_allowed_numbers();
        $view = strtolower(trim((string) $request->query('view', 'registered')));
        if (! in_array($view, ['registered', 'paid', 'remain', 'remain_present', 'remain_absent'], true)) {
            $view = 'registered';
        }

        $session = (int) $request->query('session', 0);
        if ($session !== 0 && ! in_array($session, $allowed, true)) {
            $session = 0;
        }

        $payment = strtolower(trim((string) $request->query('payment', '')));
        if (! in_array($payment, ['paid', 'unpaid'], true)) {
            $payment = '';
        }
        if ($view !== 'registered') {
            $payment = '';
        }

        $program = trim((string) $request->query('program', ''));
        $source = trim((string) $request->query('source', ''));
        $dateFrom = trim((string) $request->query('date_from', ''));
        $dateTo = trim((string) $request->query('date_to', ''));

        return [
            'view' => $view,
            'session' => $session,
            'search' => trim((string) $request->query('q', '')),
            'payment' => $payment,
            'program' => $program,
            'source' => $source,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'allowed' => $allowed,
        ];
    }

    /**
     * @param  array{view: string, session: int, search: string, allowed: list<int>}  $state
     * @return array{
     *     total: int,
     *     paid: int,
     *     remain: int,
     *     remain_present: int,
     *     remain_absent: int,
     *     registered: int,
     *     filtered: int,
     *     session_counts: array<int, array{total: int, paid: int, remain: int, remain_present: int, remain_absent: int}>
     * }
     */
    private function stats(array $state): array
    {
        $all = $this->allMemberRows();
        $scoped = $state['session'] > 0
            ? $all->where('session', $state['session'])->values()
            : $all;
        $counts = $this->bucketCounts($scoped);

        $sessionCounts = [];
        foreach ($state['allowed'] as $sessionNo) {
            $sessionCounts[$sessionNo] = $this->bucketCounts($all->where('session', $sessionNo)->values());
        }

        return [
            ...$counts,
            'registered' => $counts['total'],
            'filtered' => $this->rowsForView($state)->count(),
            'session_counts' => $sessionCounts,
        ];
    }

    /**
     * @param  Collection<int, array{session: int, payment: ?AdmissionPayment, present: bool}>  $rows
     * @return array{total: int, paid: int, remain: int, remain_present: int, remain_absent: int}
     */
    private function bucketCounts(Collection $rows): array
    {
        $paid = $rows->filter(fn (array $row) => $row['payment'] !== null)->count();
        $remain = $rows->count() - $paid;
        $remainPresent = $rows->filter(fn (array $row) => $row['payment'] === null && ! empty($row['present']))->count();

        return [
            'total' => $rows->count(),
            'paid' => $paid,
            'remain' => $remain,
            'remain_present' => $remainPresent,
            'remain_absent' => $remain - $remainPresent,
        ];
    }

    private function viewLabel(string $view): string
    {
        return match ($view) {
            'paid' => 'Payment Done',
            'remain' => 'Remain',
            'remain_present' => 'Remain Present',
            'remain_absent' => 'Remain Absent',
            default => 'Total',
        };
    }

    /**
     * @param  array{view: string, session: int, search: string, allowed: list<int>}  $state
     * @return Collection<int, array{session: int, inquiry: ?ContactInquiry, payment: ?AdmissionPayment}>
     */
    private function rowsForView(array $state): Collection
    {
        $rows = $this->allMemberRows();
        if ($state['session'] > 0) {
            $rows = $rows->where('session', $state['session'])->values();
        }
        if ($state['search'] !== '') {
            $needle = mb_strtolower($state['search']);
            $rows = $rows->filter(function (array $row) use ($needle) {
                $inquiry = $row['inquiry'] ?? null;
                $payment = $row['payment'] ?? null;
                $hay = mb_strtolower(implode(' ', array_filter([
                    (string) ($inquiry->full_name ?? ''),
                    (string) ($inquiry->email ?? ''),
                    (string) ($inquiry->mobile ?? ''),
                    (string) ($inquiry->registration_number ?? ''),
                    (string) ($inquiry->interested_program ?? ''),
                    (string) ($payment->customer_name ?? ''),
                    (string) ($payment->customer_mobile ?? ''),
                    (string) ($payment->customer_email ?? ''),
                    (string) ($payment->registration_number ?? ''),
                    (string) ($payment->merchant_txn_no ?? ''),
                ])));

                return str_contains($hay, $needle);
            })->values();
        }

        return $this->applyView($this->applyRowFilters($rows, $state), $state['view']);
    }

    /**
     * @param  Collection<int, array{session: int, inquiry: ?ContactInquiry, payment: ?AdmissionPayment, present?: bool}>  $rows
     * @return Collection<int, array{session: int, inquiry: ?ContactInquiry, payment: ?AdmissionPayment, present?: bool}>
     */
    private function applyView(Collection $rows, string $view): Collection
    {
        return match ($view) {
            'paid' => $rows->filter(fn (array $row) => $row['payment'] !== null)->values(),
            'remain' => $rows->filter(fn (array $row) => $row['payment'] === null)->values(),
            'remain_present' => $rows->filter(fn (array $row) => $row['payment'] === null && ! empty($row['present']))->values(),
            'remain_absent' => $rows->filter(fn (array $row) => $row['payment'] === null && empty($row['present']))->values(),
            default => $rows->values(),
        };
    }

    /**
     * @return Collection<int, array{session: int, inquiry: ContactInquiry, payment: ?AdmissionPayment, present: bool}>
     */
    private function allMemberRows(): Collection
    {
        return $this->allRows ??= $this->registeredRows(0, '');
    }

    /**
     * @param  Collection<int, array{session: int, inquiry: ?ContactInquiry, payment: ?AdmissionPayment}>  $rows
     * @param  array{payment: string, program: string, source: string, date_from: string, date_to: string}  $state
     * @return Collection<int, array{session: int, inquiry: ?ContactInquiry, payment: ?AdmissionPayment}>
     */
    private function applyRowFilters(Collection $rows, array $state): Collection
    {
        $payment = (string) ($state['payment'] ?? '');
        $program = (string) ($state['program'] ?? '');
        $source = (string) ($state['source'] ?? '');
        $dateFrom = (string) ($state['date_from'] ?? '');
        $dateTo = (string) ($state['date_to'] ?? '');

        if ($payment === '' && $program === '' && $source === '' && $dateFrom === '' && $dateTo === '') {
            return $rows->values();
        }

        return $rows
            ->filter(function (array $row) use ($payment, $program, $source, $dateFrom, $dateTo) {
                $inquiry = $row['inquiry'] ?? null;
                $paid = $row['payment'] ?? null;

                if ($payment === 'paid' && ! $paid) {
                    return false;
                }
                if ($payment === 'unpaid' && $paid) {
                    return false;
                }

                if ($program !== '') {
                    $rowProgram = trim((string) ($inquiry?->interested_program ?? ''));
                    if ($rowProgram !== $program) {
                        return false;
                    }
                }

                if ($source !== '') {
                    if (! $inquiry || $inquiry->resolvedFormSource() !== $source) {
                        return false;
                    }
                }

                if ($dateFrom !== '' || $dateTo !== '') {
                    $when = $inquiry?->created_at ?? $paid?->paid_at;
                    if (! $when) {
                        return false;
                    }
                    $day = $when->timezone('Asia/Kolkata')->toDateString();
                    if ($dateFrom !== '' && $day < $dateFrom) {
                        return false;
                    }
                    if ($dateTo !== '' && $day > $dateTo) {
                        return false;
                    }
                }

                return true;
            })
            ->values();
    }

    /**
     * @return Collection<int, array{session: int, inquiry: ContactInquiry, payment: ?AdmissionPayment, present: bool}>
     */
    private function registeredRows(int $sessionFilter, string $search): Collection
    {
        $map = bns_reporting_session_mobile_map(onlyIntro: false);
        $lookup = $this->paymentLookup();
        $attendance = $this->attendanceIndex();
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
            ->map(function (ContactInquiry $inquiry) use ($idsBySession, $lookup, $attendance) {
                $session = (int) ($idsBySession[(int) $inquiry->id] ?? 0);
                $mobile = ContactInquiry::normalizeMobile((string) $inquiry->mobile);

                return [
                    'session' => $session,
                    'inquiry' => $inquiry,
                    'payment' => $this->paymentForInquiry($inquiry, $lookup),
                    'present' => $session > 0 && (
                        isset($attendance['ids'][$session][(int) $inquiry->id])
                        || ($mobile !== '' && isset($attendance['mobiles'][$session][$mobile]))
                    ),
                ];
            })
            ->when($sessionFilter > 0, fn (Collection $rows) => $rows->where('session', $sessionFilter)->values())
            ->values();
    }

    /**
     * @return array{ids: array<int, array<int, true>>, mobiles: array<int, array<string, true>>}
     */
    private function attendanceIndex(): array
    {
        $ids = [];
        $mobiles = [];
        if (! Schema::hasTable('session_attendances')) {
            return compact('ids', 'mobiles');
        }

        $rows = SessionAttendance::query()
            ->get(['contact_inquiry_id', 'mobile', 'session_number', 'status']);

        foreach ($rows as $row) {
            if ((string) $row->status === SessionAttendance::STATUS_ABSENT) {
                continue;
            }
            $session = (int) $row->session_number;
            if ($session < 1) {
                continue;
            }
            $id = (int) $row->contact_inquiry_id;
            if ($id > 0) {
                $ids[$session][$id] = true;
            }
            $mobile = ContactInquiry::normalizeMobile((string) $row->mobile);
            if ($mobile !== '') {
                $mobiles[$session][$mobile] = true;
            }
        }

        return compact('ids', 'mobiles');
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
