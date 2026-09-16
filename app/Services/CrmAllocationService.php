<?php

namespace App\Services;

use App\Models\ContactInquiry;
use App\Models\CrmAssignment;
use App\Models\CrmEmployee;
use App\Models\SessionAttendance;
use App\Support\CrmLeadStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class CrmAllocationService
{
    /**
     * Divide last-session registered members evenly across all active employees.
     * Confirmed / paid members are skipped.
     */
    public function allocateUnassigned(): int
    {
        if (! Schema::hasTable('crm_employees') || ! Schema::hasTable('crm_assignments')) {
            return 0;
        }

        $sessionNo = $this->lastSessionNumber();
        if ($sessionNo < 1) {
            return 0;
        }

        $employees = CrmEmployee::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($employees->isEmpty()) {
            return 0;
        }

        $members = $this->registeredMembersForSession($sessionNo);
        if ($members->isEmpty()) {
            return 0;
        }

        $employeeIds = $employees->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
        $employeeCount = count($employeeIds);
        $created = 0;

        // Stable order so re-runs keep the same even split.
        $members = $members->sortBy('id')->values();

        foreach ($members as $index => $row) {
            /** @var ContactInquiry $inquiry */
            $inquiry = $row['inquiry'];
            $status = $row['attendance_status'];
            $employeeId = $employeeIds[$index % $employeeCount];

            $assignment = CrmAssignment::query()->updateOrCreate(
                [
                    'contact_inquiry_id' => (int) $inquiry->id,
                    'session_number' => $sessionNo,
                ],
                [
                    'crm_employee_id' => $employeeId,
                    'attendance_status' => $status,
                    'assigned_at' => now(),
                ]
            );

            if ($assignment->wasRecentlyCreated || $assignment->wasChanged()) {
                $created++;
            }
        }

        return $created;
    }

    public function allocateInquiry(ContactInquiry $inquiry): ?CrmAssignment
    {
        if (! Schema::hasTable('crm_employees') || ! Schema::hasTable('crm_assignments')) {
            return null;
        }

        if (! $this->isAllocatableInquiry($inquiry)) {
            return null;
        }

        if (CrmLeadStatus::isConfirmed($inquiry)) {
            return null;
        }

        // New registrations go only into the last-session pool.
        $sessionNo = $this->lastSessionNumber();
        if ($sessionNo < 1) {
            $sessionNo = $this->sessionForInquiry($inquiry);
        }
        if ($sessionNo < 1) {
            return null;
        }

        $existing = CrmAssignment::query()
            ->where('contact_inquiry_id', $inquiry->id)
            ->where('session_number', $sessionNo)
            ->first();
        if ($existing) {
            return $existing;
        }

        $employees = CrmEmployee::query()->where('is_active', true)->orderBy('id')->get();
        if ($employees->isEmpty()) {
            return null;
        }

        // Balance against last-session assignments only.
        $counts = [];
        foreach ($employees as $employee) {
            $counts[(int) $employee->id] = CrmAssignment::query()
                ->where('crm_employee_id', $employee->id)
                ->where('session_number', $sessionNo)
                ->count();
        }

        $employeeId = $this->lightestEmployeeId($counts);
        if ($employeeId < 1) {
            return null;
        }

        return CrmAssignment::query()->updateOrCreate(
            [
                'contact_inquiry_id' => (int) $inquiry->id,
                'session_number' => $sessionNo,
            ],
            [
                'crm_employee_id' => $employeeId,
                'attendance_status' => $this->attendanceStatus($inquiry, $sessionNo),
                'assigned_at' => now(),
            ]
        );
    }

    public function allocateThrottled(): int
    {
        $created = 0;

        Cache::lock('crm-auto-allocate', 50)->get(function () use (&$created) {
            if (Cache::get('crm-auto-allocate-tick')) {
                return;
            }

            Cache::put('crm-auto-allocate-tick', 1, now()->addHour());
            $created = $this->allocateUnassigned();
        });

        return $created;
    }

    public function lastSessionNumber(): int
    {
        $selectable = bns_intro_session_selectable_numbers();
        if ($selectable !== []) {
            return (int) max($selectable);
        }

        try {
            $scheduler = app(IntroSessionScheduleService::class);
            $forced = $scheduler->forcedSessionNumber();
            if ($forced && $forced > 0) {
                return (int) $forced;
            }
            $default = $scheduler->defaultSessionNumber();
            if ($default > 0) {
                return (int) $default;
            }
        } catch (\Throwable) {
            // fall through
        }

        $next = bns_first_introduction_session();
        if (is_array($next) && (int) ($next['session_number'] ?? 0) > 0) {
            return (int) $next['session_number'];
        }

        $allowed = bns_intro_session_allowed_numbers();

        return (int) (max($allowed ?: [0]));
    }

    /**
     * @return Collection<int, array{inquiry: ContactInquiry, attendance_status: string}>
     */
    private function registeredMembersForSession(int $sessionNo): Collection
    {
        $breakdown = bns_session_attendance_breakdown($sessionNo);
        $rows = collect();

        foreach (['present' => $breakdown['present_rows'], 'absent' => $breakdown['absent_rows']] as $status => $list) {
            foreach ($list as $inquiry) {
                if (! $inquiry instanceof ContactInquiry) {
                    continue;
                }
                if (CrmLeadStatus::isConfirmed($inquiry)) {
                    continue;
                }

                $rows->push([
                    'inquiry' => $inquiry,
                    'attendance_status' => $status === 'present' ? 'present' : 'absent',
                ]);
            }
        }

        return $rows->unique(fn (array $row) => (int) $row['inquiry']->id)->values();
    }

    private function isAllocatableInquiry(ContactInquiry $inquiry): bool
    {
        $source = (string) ($inquiry->form_source ?? '');
        if (in_array($source, ['intro-session-modal', 'pay-now-new-registration'], true)) {
            return true;
        }

        return (int) ($inquiry->intro_session_number ?? 0) > 0;
    }

    private function sessionForInquiry(ContactInquiry $inquiry): int
    {
        $allowed = bns_intro_session_allowed_numbers();
        $stored = (int) ($inquiry->intro_session_number ?? 0);
        if (in_array($stored, $allowed, true)) {
            return $stored;
        }

        $fromMobile = bns_intro_session_number_for_mobile($inquiry->mobile);
        if ($fromMobile && in_array($fromMobile, $allowed, true)) {
            return $fromMobile;
        }

        return (int) ($allowed[0] ?? 0);
    }

    private function attendanceStatus(ContactInquiry $inquiry, int $sessionNo): string
    {
        if (! Schema::hasTable('session_attendances')) {
            return 'absent';
        }

        $mobile = ContactInquiry::normalizeMobile($inquiry->mobile);
        $exists = SessionAttendance::query()
            ->where('session_number', $sessionNo)
            ->where(function ($q) use ($inquiry, $mobile) {
                $q->where('contact_inquiry_id', $inquiry->id);
                if ($mobile !== '') {
                    $q->orWhere('mobile', $mobile);
                }
            })
            ->exists();

        return $exists ? 'present' : 'absent';
    }

    /**
     * @param  array<int, int>  $counts
     */
    private function lightestEmployeeId(array $counts): int
    {
        if ($counts === []) {
            return 0;
        }

        $min = min($counts);
        foreach ($counts as $id => $count) {
            if ($count === $min) {
                return (int) $id;
            }
        }

        return (int) array_key_first($counts);
    }
}
