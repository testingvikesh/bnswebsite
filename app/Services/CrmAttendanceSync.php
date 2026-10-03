<?php

namespace App\Services;

use App\Models\ContactInquiry;
use App\Models\CrmAssignment;
use App\Models\SessionAttendance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class CrmAttendanceSync
{
    public function syncCollection(Collection $assignments): Collection
    {
        if ($assignments->isEmpty() || ! Schema::hasTable('session_attendances')) {
            return $assignments;
        }

        $lookups = [];
        foreach ($assignments->pluck('session_number')->unique() as $sessionNo) {
            $lookups[(int) $sessionNo] = $this->presentLookup((int) $sessionNo);
        }

        foreach ($assignments as $assignment) {
            $status = $this->statusFromLookup(
                $assignment,
                $lookups[(int) $assignment->session_number] ?? ['ids' => [], 'mobiles' => []]
            );
            if ((string) $assignment->attendance_status !== $status) {
                $assignment->attendance_status = $status;
                $assignment->save();
            }
        }

        return $assignments;
    }

    public function liveStatus(ContactInquiry $inquiry, int $sessionNumber): string
    {
        return $this->inquiryIsPresent($inquiry, $this->presentLookup($sessionNumber))
            ? 'present'
            : 'absent';
    }

    public function markPresentForInquiry(ContactInquiry $inquiry, int $sessionNumber): void
    {
        $this->updateInquiryAssignments($inquiry, $sessionNumber, 'present');
    }

    public function markAbsentForInquiry(ContactInquiry $inquiry, int $sessionNumber): void
    {
        $this->updateInquiryAssignments($inquiry, $sessionNumber, 'absent');
    }

    private function updateInquiryAssignments(ContactInquiry $inquiry, int $sessionNumber, string $status): void
    {
        if (! Schema::hasTable('crm_assignments')) {
            return;
        }

        CrmAssignment::query()
            ->where('session_number', $sessionNumber)
            ->where('contact_inquiry_id', $inquiry->id)
            ->update(['attendance_status' => $status]);
    }

    /**
     * @return array{ids: array<int, true>, mobiles: array<string, true>}
     */
    private function presentLookup(int $sessionNumber): array
    {
        if (! Schema::hasTable('session_attendances')) {
            return ['ids' => [], 'mobiles' => []];
        }

        $rows = SessionAttendance::query()
            ->where('session_number', $sessionNumber)
            ->get(['contact_inquiry_id', 'mobile']);

        $ids = [];
        $mobiles = [];
        foreach ($rows as $row) {
            $id = (int) $row->contact_inquiry_id;
            if ($id > 0) {
                $ids[$id] = true;
            }
            $mobile = ContactInquiry::normalizeMobile((string) $row->mobile);
            if ($mobile !== '') {
                $mobiles[$mobile] = true;
            }
        }

        return ['ids' => $ids, 'mobiles' => $mobiles];
    }

    /**
     * @param  array{ids: array<int, true>, mobiles: array<string, true>}  $lookup
     */
    private function statusFromLookup(CrmAssignment $assignment, array $lookup): string
    {
        $inquiry = $assignment->inquiry;
        if (! $inquiry) {
            return 'absent';
        }

        return $this->inquiryIsPresent($inquiry, $lookup) ? 'present' : 'absent';
    }

    /**
     * @param  array{ids: array<int, true>, mobiles: array<string, true>}  $lookup
     */
    private function inquiryIsPresent(ContactInquiry $inquiry, array $lookup): bool
    {
        if (isset($lookup['ids'][(int) $inquiry->id])) {
            return true;
        }

        $mobile = ContactInquiry::normalizeMobile((string) $inquiry->mobile);

        return $mobile !== '' && isset($lookup['mobiles'][$mobile]);
    }
}
