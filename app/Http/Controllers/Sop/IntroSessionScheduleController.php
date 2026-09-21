<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Services\IntroSessionScheduleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class IntroSessionScheduleController extends Controller
{
    public function __construct(private IntroSessionScheduleService $schedules) {}

    public function edit(): View
    {
        $this->schedules->ensureSeeded();

        $sessions = collect(bns_introduction_sessions())
            ->map(function (array $event) {
                $startsAt = null;
                $endsAt = null;
                try {
                    if (! empty($event['starts_at'])) {
                        $startsAt = Carbon::parse((string) $event['starts_at'], 'Asia/Kolkata');
                    }
                    if (! empty($event['ends_at'])) {
                        $endsAt = Carbon::parse((string) $event['ends_at'], 'Asia/Kolkata');
                    }
                } catch (\Throwable) {
                    // keep null
                }

                return [
                    'session_number' => (int) ($event['session_number'] ?? 0),
                    'title' => (string) ($event['title'] ?? ''),
                    'date_label' => (string) ($event['date'] ?? ''),
                    'time_label' => (string) ($event['time'] ?? ''),
                    'session_date' => $startsAt?->toDateString() ?? '',
                    'start_time' => $startsAt?->format('H:i') ?? '',
                    'end_time' => $endsAt?->format('H:i') ?? '',
                    'is_past' => bns_event_has_passed($event),
                ];
            })
            ->values()
            ->all();

        return view('sop.intro-session-schedules.edit', [
            'sessions' => $sessions,
            'forcedSession' => $this->schedules->forcedSessionNumber(),
            'defaultSession' => $this->schedules->defaultSessionNumber(),
            'upcoming' => bns_introduction_sessions(true),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $allowed = bns_intro_session_allowed_numbers();

        $validated = $request->validate([
            'forced_session_number' => ['nullable', 'integer'],
            'sessions' => ['required', 'array'],
            'sessions.*.session_number' => ['required', 'integer'],
            'sessions.*.title' => ['nullable', 'string', 'max:255'],
            'sessions.*.session_date' => ['required', 'date'],
            'sessions.*.start_time' => ['required', 'date_format:H:i'],
            'sessions.*.end_time' => ['required', 'date_format:H:i'],
        ]);

        foreach ($validated['sessions'] as $row) {
            $number = (int) $row['session_number'];
            if (! in_array($number, $allowed, true)) {
                continue;
            }

            $this->schedules->updateSession($number, [
                'title' => $row['title'] ?? null,
                'session_date' => $row['session_date'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'is_active' => true,
            ]);
        }

        $forced = $request->input('forced_session_number');
        if ($forced === null || $forced === '') {
            $this->schedules->setActiveSession(null);
        } else {
            $forced = (int) $forced;
            $this->schedules->setActiveSession(in_array($forced, $allowed, true) ? $forced : null);
        }

        return redirect()
            ->route('controlpanel.intro-session-schedules.edit')
            ->with('status', 'Introduction Session dates & times updated. Changes apply across the website.');
    }

    public function store(): RedirectResponse
    {
        $row = $this->schedules->createNextSession();
        $number = (int) $row->session_number;

        return redirect()
            ->route('controlpanel.intro-session-schedules.edit')
            ->with('status', 'Session '.$number.' added. Set the date and time, then Save. You can also choose it as the active session for new admissions.');
    }
}
