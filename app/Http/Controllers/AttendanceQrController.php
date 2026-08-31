<?php

namespace App\Http\Controllers;

use App\Models\AttendanceQrInvite;
use App\Services\AttendanceConfirmationMailer;
use App\Services\AttendanceModuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceQrController extends Controller
{
    public function __construct(
        private AttendanceModuleService $attendance,
        private AttendanceConfirmationMailer $confirmationMailer,
    ) {}

    public function show(string $token): View
    {
        $invite = AttendanceQrInvite::query()
            ->where('token', $token)
            ->firstOrFail();

        $event = bns_introduction_session((int) $invite->session_number) ?? [];

        return view('attendance.qr', [
            'invite' => $invite,
            'event' => $event,
            'canApprove' => $invite->isApprovable(),
            'alreadyApproved' => $invite->status === AttendanceQrInvite::STATUS_APPROVED,
            'expired' => $invite->isExpired(),
            'revoked' => $invite->status === AttendanceQrInvite::STATUS_REVOKED,
        ]);
    }

    public function approve(Request $request, string $token): RedirectResponse
    {
        $invite = AttendanceQrInvite::query()
            ->where('token', $token)
            ->firstOrFail();

        $result = $this->attendance->approveInvite($invite->fresh(), 'qr');

        if (! ($result['ok'] ?? false)) {
            return redirect()
                ->route('attendance.qr.show', ['token' => $token])
                ->with('error', $result['message'] ?? 'Unable to approve attendance.');
        }

        if (! empty($result['attendance'])) {
            try {
                $this->confirmationMailer->send($result['attendance']);
            } catch (\Throwable) {
                // Invite approval succeeded even if confirmation mail fails.
            }
        }

        return redirect()
            ->route('attendance.qr.show', ['token' => $token])
            ->with('status', $result['message'] ?? 'Attendance approved.');
    }
}
