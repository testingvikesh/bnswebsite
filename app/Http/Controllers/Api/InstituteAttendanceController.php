<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\SessionAttendance;
use App\Models\User;
use App\Services\AttendanceConfirmationMailer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class InstituteAttendanceController extends Controller
{
    public function __construct(
        private AttendanceConfirmationMailer $mailer,
    ) {}

    /**
     * Institute / introduction sessions for the attendance app dropdown.
     */
    public function sessions(): JsonResponse
    {
        abort_unless(bns_attendance_enabled(), 404);

        return response()->json([
            'ok' => true,
            'default_session_number' => $this->defaultSessionNumber(),
            'sessions' => $this->sessionOptions(),
        ]);
    }

    /**
     * Institute app: select institute/session, date (default today) + email or mobile.
     * Looks up a registered member, marks attendance with current time, approves, emails.
     */
    public function mark(Request $request): JsonResponse
    {
        abort_unless(bns_attendance_enabled(), 404);

        $allowed = bns_intro_session_allowed_numbers();

        $validated = $request->validate([
            'session_number' => ['required', 'integer', Rule::in($allowed)],
            'attendance_date' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'registration_number' => ['nullable', 'string', 'max:40'],
        ]);

        $email = trim((string) ($validated['email'] ?? ''));
        $mobile = trim((string) ($validated['mobile'] ?? ''));
        $registration = trim((string) ($validated['registration_number'] ?? ''));

        if ($email === '' && $mobile === '' && $registration === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Enter registered email, mobile, or BNS 4-digit number.',
            ], 422);
        }

        $inquiry = $this->findMember($email, $mobile, $registration);

        if (! $inquiry) {
            $inUsers = $this->existsInUsersTable($email, $mobile);

            return response()->json([
                'ok' => false,
                'not_found' => true,
                'in_users_table' => $inUsers,
                'message' => $inUsers
                    ? 'This login user was found, but there is no session member record for attendance.'
                    : 'This member was not found. Check the registered email, mobile, or BNS 4-digit number.',
            ], 404);
        }

        $sessionNumber = (int) $validated['session_number'];
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
                'name' => $inquiry->full_name,
                'full_name' => $inquiry->full_name,
                'registration_number' => $inquiry->registration_number,
                'session_number' => $sessionNumber,
                'session_label' => $this->sessionLabel($sessionNumber),
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
            'name' => $inquiry->full_name,
            'full_name' => $inquiry->full_name,
            'registration_number' => $inquiry->registration_number,
            'session_number' => $sessionNumber,
            'session_label' => $this->sessionLabel($sessionNumber),
            'attended_at' => $attendance->attended_at
                ? $attendance->attended_at->timezone('Asia/Kolkata')->format('d M Y, h:i A')
                : null,
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function sessionOptions(): array
    {
        $options = [];

        foreach (bns_introduction_sessions() as $event) {
            $number = (int) ($event['session_number'] ?? 0);
            if ($number <= 0) {
                continue;
            }

            $startsAt = trim((string) ($event['starts_at'] ?? ''));
            $date = null;
            if ($startsAt !== '') {
                try {
                    $date = Carbon::parse($startsAt, 'Asia/Kolkata')->toDateString();
                } catch (\Throwable) {
                    $date = null;
                }
            }

            $dateLabel = trim((string) ($event['date'] ?? ''));
            $venue = trim((string) ($event['venue'] ?? ''));
            $label = 'Session '.$number;
            if ($dateLabel !== '') {
                $label .= ' · '.$dateLabel;
            }
            if ($venue !== '') {
                $label .= ' · '.$venue;
            }

            $options[] = [
                'id' => $number,
                'session_number' => $number,
                'label' => $label,
                'title' => (string) ($event['title'] ?? ('Session '.$number)),
                'date' => $date,
                'date_label' => $dateLabel,
                'time' => (string) ($event['time'] ?? ''),
                'venue' => $venue,
            ];
        }

        return $options;
    }

    private function defaultSessionNumber(): int
    {
        $todaySession = bns_intro_session_number_for_date(
            now('Asia/Kolkata')->toDateString()
        );
        if ($todaySession) {
            return $todaySession;
        }

        $forced = config('intro_session_form.forced_session_number');
        if (is_numeric($forced) && (int) $forced > 0) {
            return (int) $forced;
        }

        return (int) config('intro_session_form.default_session_number', 5);
    }

    private function sessionLabel(int $sessionNumber): string
    {
        foreach ($this->sessionOptions() as $option) {
            if ((int) $option['session_number'] === $sessionNumber) {
                return (string) $option['label'];
            }
        }

        return 'Session '.$sessionNumber;
    }

    private function findMember(string $email, string $mobile, string $registration): ?ContactInquiry
    {
        $query = ContactInquiry::primaryFormsQuery()->orderByDesc('id');

        if ($email !== '') {
            $query->whereRaw('LOWER(TRIM(email)) = ?', [strtolower($email)]);
        } elseif ($mobile !== '') {
            $digits = preg_replace('/\D+/', '', $mobile) ?: '';
            $last10 = substr($digits, -10);
            $query->where(function ($q) use ($last10, $digits) {
                $q->where('mobile', 'like', '%'.$last10)
                    ->orWhere('mobile', 'like', '%'.$digits)
                    ->orWhere('whatsapp', 'like', '%'.$last10);
            });
        } else {
            $this->applyRegistrationFilter($query, $registration);
        }

        return $query->first();
    }

    private function applyRegistrationFilter(Builder $query, string $registration): void
    {
        $raw = strtoupper(preg_replace('/\s+/', '', $registration) ?: '');

        if (preg_match('/^BNS-ENQ-\d{4}-\d+$/', $raw) === 1) {
            $query->where('registration_number', $raw);

            return;
        }

        $digits = preg_replace('/\D+/', '', $raw) ?: '';
        if ($digits === '') {
            $query->whereRaw('1 = 0');

            return;
        }

        $sequence = str_pad(substr($digits, -4), 4, '0', STR_PAD_LEFT);
        $year = now('Asia/Kolkata')->format('Y');
        $currentYearNumber = sprintf('BNS-ENQ-%s-%s', $year, $sequence);

        $query->where(function ($q) use ($currentYearNumber, $sequence) {
            $q->where('registration_number', $currentYearNumber)
                ->orWhereRaw('SUBSTRING_INDEX(registration_number, "-", -1) = ?', [$sequence]);
        });
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
