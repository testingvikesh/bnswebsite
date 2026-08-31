<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\AttendanceQrInvite;
use App\Models\ContactInquiry;
use App\Services\AttendanceModuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceModuleController extends Controller
{
    public function __construct(private AttendanceModuleService $attendance) {}

    public function index(Request $request): View
    {
        $allowed = bns_intro_session_allowed_numbers();
        $session = (int) $request->query('session', $allowed[0] ?? 2);
        if (! in_array($session, $allowed, true)) {
            $session = $allowed[0] ?? 2;
        }

        $list = (string) $request->query('list', 'all');
        if (! in_array($list, ['all', 'present', 'absent'], true)) {
            $list = 'all';
        }

        $search = trim((string) $request->query('search', ''));
        $breakdown = bns_session_attendance_breakdown($session);
        $participants = match ($list) {
            'present' => $breakdown['present_rows'],
            'absent' => $breakdown['absent_rows'],
            default => collect($breakdown['present_rows'])->merge($breakdown['absent_rows'])->unique('id')->sortBy('id')->values(),
        };

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $participants = $participants->filter(function (ContactInquiry $row) use ($needle) {
                $hay = mb_strtolower(implode(' ', [
                    (string) $row->full_name,
                    (string) $row->email,
                    (string) $row->mobile,
                    (string) $row->registration_number,
                ]));

                return str_contains($hay, $needle);
            })->values();
        }

        $presentIds = collect($breakdown['present_rows'])->pluck('id')->all();
        $invites = AttendanceQrInvite::query()
            ->where('session_number', $session)
            ->whereIn('contact_inquiry_id', $participants->pluck('id')->all() ?: [0])
            ->get()
            ->keyBy('contact_inquiry_id');

        $event = bns_introduction_session($session) ?? [];

        return view('sop.attendance.index', [
            'session' => $session,
            'allowedSessions' => $allowed,
            'list' => $list,
            'search' => $search,
            'event' => $event,
            'participants' => $participants,
            'presentIds' => $presentIds,
            'invites' => $invites,
            'stats' => [
                'registered' => $breakdown['registered'],
                'present' => $breakdown['present'],
                'absent' => $breakdown['absent'],
                'filtered' => $participants->count(),
                'with_email' => $participants->filter(fn (ContactInquiry $row) => filled($row->email))->count(),
            ],
        ]);
    }

    public function mark(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'session' => ['required', 'integer'],
            'inquiry_id' => ['required', 'integer', 'exists:contact_inquiries,id'],
            'action' => ['required', 'in:present,absent'],
        ]);

        $session = (int) $validated['session'];
        $inquiry = ContactInquiry::query()->findOrFail((int) $validated['inquiry_id']);

        if ($validated['action'] === 'present') {
            $this->attendance->markPresent($inquiry, $session, 'admin');
            $msg = 'Marked Present: '.$inquiry->full_name;
        } else {
            $this->attendance->markAbsent($inquiry, $session);
            $msg = 'Marked Absent: '.$inquiry->full_name;
        }

        return back()->with('status', $msg);
    }

    public function bulkMark(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'session' => ['required', 'integer'],
            'inquiry_ids' => ['required', 'array', 'min:1'],
            'inquiry_ids.*' => ['integer', 'exists:contact_inquiries,id'],
            'action' => ['required', 'in:present,absent'],
        ]);

        $session = (int) $validated['session'];
        $count = 0;

        foreach ($validated['inquiry_ids'] as $id) {
            $inquiry = ContactInquiry::query()->find((int) $id);
            if (! $inquiry) {
                continue;
            }
            if ($validated['action'] === 'present') {
                $this->attendance->markPresent($inquiry, $session, 'admin');
            } else {
                $this->attendance->markAbsent($inquiry, $session);
            }
            $count++;
        }

        return back()->with('status', $count.' participant(s) marked as '.ucfirst($validated['action']).'.');
    }

    public function sendMail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'session' => ['required', 'integer'],
            'inquiry_ids' => ['required', 'array', 'min:1'],
            'inquiry_ids.*' => ['integer', 'exists:contact_inquiries,id'],
        ]);

        $session = (int) $validated['session'];
        $ok = 0;
        $fail = 0;
        $errors = [];

        foreach ($validated['inquiry_ids'] as $id) {
            $inquiry = ContactInquiry::query()->find((int) $id);
            if (! $inquiry) {
                $fail++;
                continue;
            }

            $result = $this->attendance->sendInvite($inquiry, $session);
            if ($result['ok']) {
                $ok++;
            } else {
                $fail++;
                $errors[] = $result['message'];
            }
        }

        $message = "Attendance QR mails sent: {$ok} success".($fail ? ", {$fail} failed" : '').'.';
        if ($errors !== []) {
            $message .= ' '.implode(' | ', array_slice($errors, 0, 3));
        }

        return back()->with($fail && ! $ok ? 'error' : 'status', $message);
    }
}
