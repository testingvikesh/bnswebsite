<?php

namespace App\Services;

use App\Mail\AttendanceConfirmedMail;
use App\Models\SessionAttendance;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AttendanceConfirmationMailer
{
    public function send(SessionAttendance $attendance): void
    {
        $email = trim((string) ($attendance->email ?? ''));

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $event = bns_introduction_session((int) $attendance->session_number) ?? [];

        try {
            Mail::to($email)->send(new AttendanceConfirmedMail(
                attendance: $attendance,
                event: $event,
            ));
        } catch (\Throwable $exception) {
            Log::error('Attendance confirmation email failed', [
                'email' => $email,
                'registration_number' => $attendance->registration_number,
                'session_number' => $attendance->session_number,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
