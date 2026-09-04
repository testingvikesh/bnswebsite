<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\SessionAttendance;
use App\Models\User;
use App\Services\AttendanceConfirmationMailer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InstituteAttendanceController extends Controller
{
    public function __construct(
        private AttendanceConfirmationMailer $mailer,
    ) {}

    /**
     * Institute app: date (default today) + email or mobile.
     * Looks up a registered member, marks attendance with current time, approves, emails.
     */
    public function mark(Request $request): JsonResponse
    {
        abort_unless(bns_attendance_enabled(), 404);

        $validated = $request->validate([
            'attendance_date' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
        ]);

        $email = trim((string) ($validated['email'] ?? ''));
        $mobile = trim((string) ($validated['mobile'] ?? ''));

        if ($email === '' && $mobile === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Enter registered email or mobile number.',
            ], 422);
        }

        $inquiry = $this->findMember($email, $mobile);

        if (! $inquiry) {
            $inUsers = $this->existsInUsersTable($email, $mobile);

            return response()->json([
                'ok' => false,
                'not_found' => true,
                'in_users_table' => $inUsers,
                'message' => $inUsers
                    ? 'This login user was found, but there is no session member record for attendance.'
                    : 'This member was not found. Check the registered email or mobile number.',
            ], 404);
        }

        $sessionNumber = bns_intro_session_number_for_mobile($inquiry->mobile) ?: (int) ($inquiry->intro_session_number ?: 1);
        $attendedAt = $this->resolveAttendedAt($validated['attendance_date'] ?? null);

        $existing = SessionAttendance::query()
            ->where('contact_inquiry_id', $inquiry->id)
            ->where('session_number', $sessionNumber)
            ->first();

        if ($existing) {
            return response()->json([
                'ok' => true,
                'already_attended' => true,
                'mail_sent' => false,
                'message' => 'Attendance already marked for this session.',
                'full_name' => $inquiry->full_name,
                'registration_number' => $inquiry->registration_number,
                'session_label' => 'Session '.$sessionNumber,
                'attended_at' => $existing->attended_at
                    ? $existing->attended_at->timezone('Asia/Kolkata')->format('d M Y, h:i A')
                    : null,
            ]);
        }

        $programs = config('pay_now.programs', []);
        $programKey = (string) ($inquiry->interested_program ?? '');
        $programLabel = $programs[$programKey] ?? ($programKey !== '' ? $programKey : null);

        $attendance = SessionAttendance::query()->create([
            'contact_inquiry_id' => $inquiry->id,
            'registration_number' => $inquiry->registration_number,
            'session_number' => $sessionNumber,
            'full_name' => $inquiry->full_name,
            'email' => $inquiry->email,
            'mobile' => $inquiry->mobile,
            'program' => $programLabel,
            'status' => SessionAttendance::STATUS_PRESENT,
            'marked_via' => SessionAttendance::VIA_INSTITUTE,
            'attended_at' => $attendedAt,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        $this->mailer->send($attendance);

        return response()->json([
            'ok' => true,
            'already_attended' => false,
            'mail_sent' => filled($inquiry->email),
            'message' => 'Attendance approved. Confirmation email has been sent.',
            'full_name' => $inquiry->full_name,
            'registration_number' => $inquiry->registration_number,
            'session_label' => $attendance->sessionLabel(),
            'attended_at' => $attendance->attended_at
                ? $attendance->attended_at->timezone('Asia/Kolkata')->format('d M Y, h:i A')
                : null,
        ]);
    }

    private function findMember(string $email, string $mobile): ?ContactInquiry
    {
        $query = ContactInquiry::primaryFormsQuery()->orderByDesc('id');

        if ($email !== '') {
            $query->whereRaw('LOWER(TRIM(email)) = ?', [strtolower($email)]);
        } else {
            $digits = preg_replace('/\D+/', '', $mobile) ?: '';
            $last10 = substr($digits, -10);
            $query->where(function ($q) use ($last10, $digits) {
                $q->where('mobile', 'like', '%'.$last10)
                    ->orWhere('mobile', 'like', '%'.$digits)
                    ->orWhere('whatsapp', 'like', '%'.$last10);
            });
        }

        return $query->first();
    }

    private function existsInUsersTable(string $email, string $mobile): bool
    {
        if ($email !== '') {
            return User::query()
                ->whereRaw('LOWER(TRIM(email)) = ?', [strtolower($email)])
                ->exists();
        }

        if ($mobile === '' || ! \Illuminate\Support\Facades\Schema::hasColumn('users', 'mobile')) {
            return false;
        }

        $last10 = substr(preg_replace('/\D+/', '', $mobile) ?: '', -10);

        return User::query()->where('mobile', 'like', '%'.$last10)->exists();
    }

    private function resolveAttendedAt(?string $date): Carbon
    {
        $tz = 'Asia/Kolkata';
        $now = now($tz);
        $day = $date
            ? Carbon::parse($date, $tz)->startOfDay()
            : $now->copy()->startOfDay();

        return $day->copy()->setTimeFrom($now);
    }
}
