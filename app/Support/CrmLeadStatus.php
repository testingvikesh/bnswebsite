<?php

namespace App\Support;

use App\Models\AdmissionPayment;
use App\Models\ContactInquiry;
use App\Models\CrmAssignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class CrmLeadStatus
{
    public const QUICK_REGISTER_SOURCE = 'register-quick-modal';

    public static function isConfirmed(?ContactInquiry $inquiry): bool
    {
        if (! $inquiry) {
            return false;
        }

        if (Schema::hasColumn('contact_inquiries', 'admission_confirmed_at') && $inquiry->admission_confirmed_at) {
            return true;
        }

        if ($inquiry->form_source === self::QUICK_REGISTER_SOURCE) {
            return true;
        }

        return in_array((int) $inquiry->id, self::paidInquiryIds(), true);
    }

    public static function confirm(ContactInquiry $inquiry): void
    {
        if (! Schema::hasColumn('contact_inquiries', 'admission_confirmed_at')) {
            return;
        }

        if ($inquiry->admission_confirmed_at) {
            return;
        }

        $inquiry->forceFill(['admission_confirmed_at' => now()])->save();
    }

    /**
     * Pay Now / payment: match this mobile to CRM call-list members and hide them.
     */
    public static function confirmCallListByMobile(?string $mobile, ?ContactInquiry $primary = null): void
    {
        if ($primary) {
            self::confirm($primary);
        }

        $ids = self::inquiryIdsMatchingMobile($mobile);
        if ($ids === []) {
            return;
        }

        if (! Schema::hasTable('crm_assignments')) {
            return;
        }

        $assignedIds = CrmAssignment::query()
            ->whereIn('contact_inquiry_id', $ids)
            ->pluck('contact_inquiry_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->all();

        if ($assignedIds === []) {
            return;
        }

        ContactInquiry::query()
            ->whereIn('id', $assignedIds)
            ->get()
            ->each(fn (ContactInquiry $inquiry) => self::confirm($inquiry));
    }

    public static function confirmFromPayment(AdmissionPayment $payment): void
    {
        $inquiry = self::findInquiryForPayment($payment);
        self::confirmCallListByMobile($payment->customer_mobile ?: optional($inquiry)->mobile, $inquiry);
    }

    public static function excludeConfirmed(Builder $query): Builder
    {
        $ids = array_values(array_unique(array_merge(
            self::confirmedInquiryIds(),
            self::paidInquiryIds()
        )));
        if ($ids === []) {
            return $query;
        }

        return $query->whereNotIn('contact_inquiry_id', $ids);
    }

    /** @return list<int> */
    public static function confirmedInquiryIds(): array
    {
        if (! Schema::hasTable('contact_inquiries')) {
            return [];
        }

        return self::confirmedInquiriesQuery()
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public static function confirmedInquiriesQuery(): Builder
    {
        $query = ContactInquiry::query();

        $query->where(function ($q) {
            $q->where('form_source', self::QUICK_REGISTER_SOURCE);
            if (Schema::hasColumn('contact_inquiries', 'admission_confirmed_at')) {
                $q->orWhereNotNull('admission_confirmed_at');
            }
            $paidIds = self::paidInquiryIds();
            if ($paidIds !== []) {
                $q->orWhereIn('id', $paidIds);
            }
        });

        if (Schema::hasColumn('contact_inquiries', 'admission_confirmed_at')) {
            return $query->latest('admission_confirmed_at')->latest('id');
        }

        return $query->latest('id');
    }

    /** @return list<int> */
    public static function inquiryIdsMatchingMobile(?string $mobile): array
    {
        $normalized = ContactInquiry::normalizeMobile($mobile);
        if ($normalized === '' || ! Schema::hasTable('contact_inquiries')) {
            return [];
        }

        return ContactInquiry::query()
            ->where(function ($q) use ($normalized) {
                $q->whereRaw(self::mobileLast10Sql('mobile').' = ?', [$normalized])
                    ->orWhereRaw(self::mobileLast10Sql('whatsapp').' = ?', [$normalized]);
            })
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /** @return list<int> */
    public static function paidInquiryIds(): array
    {
        static $cached = null;
        if (is_array($cached)) {
            return $cached;
        }

        if (! Schema::hasTable('admission_payments') || ! Schema::hasTable('contact_inquiries')) {
            return $cached = [];
        }

        $payments = AdmissionPayment::query()
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->get(['payable_type', 'payable_id', 'registration_number', 'customer_mobile']);

        $ids = [];
        $regs = [];

        foreach ($payments as $payment) {
            if ($payment->payable_type === ContactInquiry::class && $payment->payable_id) {
                $ids[] = (int) $payment->payable_id;
            }

            $reg = trim((string) $payment->registration_number);
            if ($reg !== '') {
                $regs[] = $reg;
            }

            foreach (self::inquiryIdsMatchingMobile($payment->customer_mobile) as $id) {
                $ids[] = $id;
            }
        }

        if ($regs !== []) {
            $ids = array_merge(
                $ids,
                ContactInquiry::query()
                    ->whereIn('registration_number', array_values(array_unique($regs)))
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->all()
            );
        }

        return $cached = array_values(array_unique($ids));
    }

    public static function findInquiryForPayment(AdmissionPayment $payment): ?ContactInquiry
    {
        $payable = $payment->relationLoaded('payable') ? $payment->payable : $payment->payable()->first();
        if ($payable instanceof ContactInquiry) {
            return $payable;
        }

        $reg = trim((string) $payment->registration_number);
        if ($reg !== '') {
            $byReg = ContactInquiry::query()->where('registration_number', $reg)->orderByDesc('id')->first();
            if ($byReg) {
                return $byReg;
            }
        }

        $mobile = ContactInquiry::normalizeMobile($payment->customer_mobile);
        if ($mobile === '') {
            return null;
        }

        $ids = self::inquiryIdsMatchingMobile($payment->customer_mobile);
        if ($ids === []) {
            return null;
        }

        return ContactInquiry::query()->whereIn('id', $ids)->orderByDesc('id')->first();
    }

    public static function successfulPaymentsQuery(): Builder
    {
        return AdmissionPayment::query()
            ->with('payable')
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->latest('paid_at')
            ->latest('id');
    }

    public static function filterPaymentsForEmployee(Builder $query, int $employeeId): Builder
    {
        $assignments = CrmAssignment::query()
            ->with('inquiry')
            ->where('crm_employee_id', $employeeId)
            ->get();

        $inquiryIds = $assignments->pluck('contact_inquiry_id')->map(fn ($id) => (int) $id)->all();
        $regs = $assignments
            ->map(fn (CrmAssignment $row) => trim((string) optional($row->inquiry)->registration_number))
            ->filter()
            ->unique()
            ->values()
            ->all();
        $mobiles = $assignments
            ->map(fn (CrmAssignment $row) => ContactInquiry::normalizeMobile(optional($row->inquiry)->mobile))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $query->where(function ($q) use ($inquiryIds, $regs, $mobiles) {
            $hasFilter = false;

            if ($inquiryIds !== []) {
                $hasFilter = true;
                $q->orWhere(function ($inner) use ($inquiryIds) {
                    $inner->where('payable_type', ContactInquiry::class)
                        ->whereIn('payable_id', $inquiryIds);
                });
            }

            if ($regs !== []) {
                $hasFilter = true;
                $q->orWhereIn('registration_number', $regs);
            }

            if ($mobiles !== []) {
                $hasFilter = true;
                $q->orWhere(function ($inner) use ($mobiles) {
                    foreach ($mobiles as $index => $mobile) {
                        $method = $index === 0 ? 'whereRaw' : 'orWhereRaw';
                        $inner->{$method}(self::mobileLast10Sql('customer_mobile').' = ?', [$mobile]);
                    }
                });
            }

            if (! $hasFilter) {
                $q->whereRaw('1 = 0');
            }
        });
    }

    public static function inquiryHasSuccessfulPayment(?ContactInquiry $inquiry): bool
    {
        if (! $inquiry || ! Schema::hasTable('admission_payments')) {
            return false;
        }

        $reg = trim((string) $inquiry->registration_number);
        $mobile = ContactInquiry::normalizeMobile($inquiry->mobile);

        return AdmissionPayment::query()
            ->where('status', AdmissionPayment::STATUS_SUCCESS)
            ->where(function ($q) use ($inquiry, $reg, $mobile) {
                $q->where(function ($inner) use ($inquiry) {
                    $inner->where('payable_type', ContactInquiry::class)
                        ->where('payable_id', $inquiry->id);
                });
                if ($reg !== '') {
                    $q->orWhere('registration_number', $reg);
                }
                if ($mobile !== '') {
                    $q->orWhereRaw(self::mobileLast10Sql('customer_mobile').' = ?', [$mobile]);
                }
            })
            ->exists();
    }

    public static function applySearch(Builder $query, string $search, array $columns): Builder
    {
        $search = trim($search);
        if ($search === '') {
            return $query;
        }

        $like = '%'.$search.'%';

        return $query->where(function ($q) use ($columns, $like) {
            foreach ($columns as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $q->{$method}($column, 'like', $like);
            }
        });
    }

    private static function mobileLast10Sql(string $column): string
    {
        $allowed = ['mobile', 'whatsapp', 'customer_mobile'];
        if (! in_array($column, $allowed, true)) {
            $column = 'mobile';
        }

        return "RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(IFNULL({$column},''), ' ', ''), '-', ''), '+', ''), '(', ''), ')', ''), 10)";
    }
}
