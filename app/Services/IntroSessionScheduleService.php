<?php

namespace App\Services;

use App\Models\IntroSessionSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class IntroSessionScheduleService
{
    public const KEY_FORCED_SESSION = 'intro_forced_session_number';

    public const KEY_DEFAULT_SESSION = 'intro_default_session_number';

    /** @var array<int, array<string, mixed>>|null */
    private static ?array $overrideCache = null;

    /**
     * Seed schedule rows from config/events.php when the table is empty.
     */
    public function ensureSeeded(): void
    {
        if (! $this->ready()) {
            return;
        }

        if (IntroSessionSchedule::query()->exists()) {
            $this->renameSessionSevenTitle();

            return;
        }

        foreach (config('events.events', []) as $event) {
            if (! is_array($event) || ($event['type'] ?? '') !== 'introduction') {
                continue;
            }

            $number = (int) ($event['session_number'] ?? 0);
            if ($number <= 0) {
                continue;
            }

            IntroSessionSchedule::query()->create([
                'session_number' => $number,
                'title' => (string) ($event['title'] ?? 'Introduction Session'),
                'date_label' => (string) ($event['date'] ?? ''),
                'time_label' => (string) ($event['time'] ?? ''),
                'starts_at' => $event['starts_at'] ?? null,
                'ends_at' => $event['ends_at'] ?? null,
                'timezone' => (string) ($event['timezone'] ?? 'Asia/Kolkata'),
                'is_active' => true,
            ]);
        }

        self::$overrideCache = null;
    }

    /**
     * @return array<int, array<string, mixed>> keyed by session_number
     */
    public function overridesBySession(): array
    {
        if (self::$overrideCache !== null) {
            return self::$overrideCache;
        }

        if (! $this->ready()) {
            return self::$overrideCache = [];
        }

        $this->ensureSeeded();

        $map = [];
        foreach (IntroSessionSchedule::query()->orderBy('session_number')->get() as $row) {
            $map[(int) $row->session_number] = [
                'title' => $row->title,
                'date' => $row->date_label,
                'time' => $row->time_label,
                'starts_at' => $row->starts_at?->format('Y-m-d H:i:s'),
                'ends_at' => $row->ends_at?->format('Y-m-d H:i:s'),
                'timezone' => $row->timezone ?: 'Asia/Kolkata',
                'is_active' => (bool) $row->is_active,
            ];
        }

        return self::$overrideCache = $map;
    }

    /**
     * Merge DB schedule over a config event array.
     *
     * @param  array<string, mixed>  $event
     * @return array<string, mixed>
     */
    public function applyToEvent(array $event): array
    {
        $number = (int) ($event['session_number'] ?? 0);
        if ($number <= 0) {
            return $event;
        }

        $override = $this->overridesBySession()[$number] ?? null;
        if (! is_array($override)) {
            return $event;
        }

        foreach (['title', 'date', 'time', 'starts_at', 'ends_at', 'timezone'] as $key) {
            $value = $override[$key] ?? null;
            if ($value !== null && $value !== '') {
                $event[$key] = $value;
            }
        }

        return $event;
    }

    /**
     * @param  array{
     *     session_date?: string,
     *     start_time?: string,
     *     end_time?: string,
     *     title?: string|null,
     *     is_active?: bool
     * }  $input
     */
    public function updateSession(int $sessionNumber, array $input): IntroSessionSchedule
    {
        $this->ensureSeeded();

        $timezone = 'Asia/Kolkata';
        $date = trim((string) ($input['session_date'] ?? ''));
        $startTime = trim((string) ($input['start_time'] ?? ''));
        $endTime = trim((string) ($input['end_time'] ?? ''));

        $startsAt = Carbon::createFromFormat('Y-m-d H:i', $date.' '.$startTime, $timezone);
        $endsAt = Carbon::createFromFormat('Y-m-d H:i', $date.' '.$endTime, $timezone);

        if ($endsAt->lte($startsAt)) {
            $endsAt = $startsAt->copy()->addHours(2);
        }

        $row = IntroSessionSchedule::query()->firstOrNew(['session_number' => $sessionNumber]);
        $row->fill([
            'title' => trim((string) ($input['title'] ?? '')) ?: 'Introduction Session',
            'date_label' => $this->formatDateLabel($startsAt),
            'time_label' => $this->formatTimeLabel($startsAt, $endsAt),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'timezone' => $timezone,
            'is_active' => (bool) ($input['is_active'] ?? true),
        ]);
        $row->save();

        self::$overrideCache = null;

        return $row;
    }

    public function nextSessionNumber(): int
    {
        $this->ensureSeeded();

        $max = 0;
        if ($this->ready()) {
            $max = (int) IntroSessionSchedule::query()->max('session_number');
        }

        foreach (config('intro_session_form.allowed_session_numbers', []) as $number) {
            $max = max($max, (int) $number);
        }

        return $max + 1;
    }

    public function createNextSession(): IntroSessionSchedule
    {
        $this->ensureSeeded();

        $next = $this->nextSessionNumber();
        $last = $this->ready()
            ? IntroSessionSchedule::query()->orderByDesc('session_number')->first()
            : null;

        $timezone = 'Asia/Kolkata';
        if ($last?->starts_at) {
            $startsAt = Carbon::parse($last->starts_at, $timezone)->addWeeks(2);
            $endsAt = $last->ends_at
                ? Carbon::parse($last->ends_at, $timezone)->addWeeks(2)
                : $startsAt->copy()->addMinutes(90);
        } else {
            $startsAt = Carbon::now($timezone)->addWeek()->setTime(19, 0);
            $endsAt = $startsAt->copy()->addMinutes(90);
        }

        return $this->updateSession($next, [
            'title' => 'Introduction Session',
            'session_date' => $startsAt->toDateString(),
            'start_time' => $startsAt->format('H:i'),
            'end_time' => $endsAt->format('H:i'),
            'is_active' => true,
        ]);
    }

    public function forcedSessionNumber(): ?int
    {
        $settings = app(SiteSettingsService::class);
        $raw = trim((string) $settings->get(self::KEY_FORCED_SESSION, ''));

        if ($raw === '') {
            $config = config('intro_session_form.forced_session_number');

            return $config === null || $config === '' ? null : (int) $config;
        }

        if (strtolower($raw) === 'null' || $raw === '0') {
            return null;
        }

        return (int) $raw;
    }

    public function defaultSessionNumber(): int
    {
        $settings = app(SiteSettingsService::class);
        $raw = trim((string) $settings->get(self::KEY_DEFAULT_SESSION, ''));

        if ($raw !== '') {
            return (int) $raw;
        }

        return (int) config('intro_session_form.default_session_number', 1);
    }

    public function setActiveSession(?int $sessionNumber): void
    {
        $settings = app(SiteSettingsService::class);

        if ($sessionNumber === null || $sessionNumber <= 0) {
            $settings->set(self::KEY_FORCED_SESSION, 'null');

            return;
        }

        $settings->set(self::KEY_FORCED_SESSION, (string) $sessionNumber);
        $settings->set(self::KEY_DEFAULT_SESSION, (string) $sessionNumber);
    }

    public function clearCache(): void
    {
        self::$overrideCache = null;
    }

    public function formatDateLabel(Carbon $startsAt): string
    {
        return $startsAt->format('d F Y').' ('.$startsAt->format('l').')';
    }

    public function formatTimeLabel(Carbon $startsAt, Carbon $endsAt): string
    {
        return $startsAt->format('g:i A').' – '.$endsAt->format('g:i A');
    }

    private function renameSessionSevenTitle(): void
    {
        $row = IntroSessionSchedule::query()->where('session_number', 7)->first();
        if ($row === null) {
            return;
        }

        $title = trim((string) $row->title);
        if ($title !== '' && $title !== 'Introduction Session 7' && strcasecmp($title, 'Session 7') !== 0) {
            return;
        }

        $row->title = 'Introduction Session';
        $row->save();
        self::$overrideCache = null;
    }

    private function ready(): bool
    {
        try {
            return Schema::hasTable('intro_session_schedules');
        } catch (\Throwable) {
            return false;
        }
    }
}
