<?php

namespace App\Services;

use App\Mail\AttendanceInviteMail;
use App\Models\AttendanceQrInvite;
use App\Models\ContactInquiry;
use App\Models\SessionAttendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AttendanceModuleService
{
    /**
     * @return array{ok: bool, message: string, invite?: AttendanceQrInvite}
     */
    public function sendInvite(ContactInquiry $inquiry, int $sessionNumber): array
    {
        $email = trim((string) ($inquiry->email ?? ''));
        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'message' => 'Valid email not found for '.$inquiry->full_name];
        }

        $event = bns_introduction_session($sessionNumber) ?? [];
        if ($event === []) {
            return ['ok' => false, 'message' => 'Session configuration not found.'];
        }

        $invite = AttendanceQrInvite::query()->updateOrCreate(
            [
                'contact_inquiry_id' => $inquiry->id,
                'session_number' => $sessionNumber,
            ],
            [
                'token' => AttendanceQrInvite::makeToken(),
                'email' => $email,
                'full_name' => $inquiry->full_name,
                'mobile' => $inquiry->mobile,
                'registration_number' => $inquiry->registration_number,
                'status' => AttendanceQrInvite::STATUS_PENDING,
                'invite_sent_at' => now(),
                'expires_at' => now()->addDays(14),
                'approved_at' => null,
                'approved_via' => null,
                'approved_by' => null,
                'session_attendance_id' => null,
                'sent_by' => Auth::id(),
            ]
        );

        try {
            Mail::to($email)->send(new AttendanceInviteMail($invite, $event));
        } catch (\Throwable $e) {
            Log::error('Attendance invite email failed', [
                'email' => $email,
                'inquiry_id' => $inquiry->id,
                'session' => $sessionNumber,
                'message' => $e->getMessage(),
            ]);

            return ['ok' => false, 'message' => 'Mail failed for '.$email.': '.$e->getMessage()];
        }

        return ['ok' => true, 'message' => 'Invite mailed to '.$email, 'invite' => $invite];
    }

    /**
     * @return array{ok: bool, message: string, attendance?: SessionAttendance}
     */
    public function approveInvite(AttendanceQrInvite $invite, string $via = 'qr', ?int $userId = null): array
    {
        if ($invite->status === AttendanceQrInvite::STATUS_APPROVED && $invite->session_attendance_id) {
            return [
                'ok' => true,
                'message' => 'Attendance already approved.',
                'attendance' => SessionAttendance::query()->find($invite->session_attendance_id),
            ];
        }

        if ($invite->status === AttendanceQrInvite::STATUS_REVOKED) {
            return ['ok' => false, 'message' => 'This attendance invite has been revoked.'];
        }

        if ($invite->isExpired()) {
            return ['ok' => false, 'message' => 'This attendance QR invite has expired.'];
        }

        $inquiry = $invite->inquiry;
        if (! $inquiry) {
            return ['ok' => false, 'message' => 'Participant record not found.'];
        }

        $attendance = DB::transaction(function () use ($invite, $inquiry, $via, $userId) {
            $attendance = SessionAttendance::query()->updateOrCreate(
                [
                    'contact_inquiry_id' => $inquiry->id,
                    'session_number' => (int) $invite->session_number,
                ],
                [
                    'registration_number' => $inquiry->registration_number,
                    'full_name' => $inquiry->full_name,
                    'email' => $inquiry->email,
                    'mobile' => $inquiry->mobile,
                    'program' => $inquiry->interested_program ?: $inquiry->category,
                    'status' => SessionAttendance::STATUS_PRESENT,
                    'marked_via' => $via === 'admin' ? SessionAttendance::VIA_ADMIN : SessionAttendance::VIA_QR,
                    'qr_token' => $invite->token,
                    'attended_at' => now(),
                    'ip_address' => request()->ip(),
                    'user_agent' => substr((string) request()->userAgent(), 0, 500),
                ]
            );

            $invite->update([
                'status' => AttendanceQrInvite::STATUS_APPROVED,
                'approved_at' => now(),
                'approved_via' => $via,
                'approved_by' => $userId,
                'session_attendance_id' => $attendance->id,
            ]);

            return $attendance;
        });

        return [
            'ok' => true,
            'message' => 'Attendance marked present successfully.',
            'attendance' => $attendance,
        ];
    }

    public function markPresent(ContactInquiry $inquiry, int $sessionNumber, string $via = 'admin'): SessionAttendance
    {
        return SessionAttendance::query()->updateOrCreate(
            [
                'contact_inquiry_id' => $inquiry->id,
                'session_number' => $sessionNumber,
            ],
            [
                'registration_number' => $inquiry->registration_number,
                'full_name' => $inquiry->full_name,
                'email' => $inquiry->email,
                'mobile' => $inquiry->mobile,
                'program' => $inquiry->interested_program ?: $inquiry->category,
                'status' => SessionAttendance::STATUS_PRESENT,
                'marked_via' => $via,
                'attended_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 500),
            ]
        );
    }

    public function markAbsent(ContactInquiry $inquiry, int $sessionNumber): void
    {
        SessionAttendance::query()
            ->where('contact_inquiry_id', $inquiry->id)
            ->where('session_number', $sessionNumber)
            ->delete();

        AttendanceQrInvite::query()
            ->where('contact_inquiry_id', $inquiry->id)
            ->where('session_number', $sessionNumber)
            ->where('status', AttendanceQrInvite::STATUS_PENDING)
            ->update(['status' => AttendanceQrInvite::STATUS_REVOKED]);
    }
}
