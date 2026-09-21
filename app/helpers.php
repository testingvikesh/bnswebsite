<?php

if (! function_exists('bns_document_title')) {
    /**
     * Public browser tab title. Home is "BNS School"; other pages append the brand.
     */
    function bns_document_title(?string $pageTitle = null): string
    {
        $siteTitle = 'BNS School';
        $pageTitle = trim((string) $pageTitle);

        if ($pageTitle === '' || strcasecmp($pageTitle, 'Home') === 0 || strcasecmp($pageTitle, $siteTitle) === 0) {
            return $siteTitle;
        }

        if (str_contains($pageTitle, $siteTitle) || str_contains($pageTitle, 'Business Navachar School')) {
            return $pageTitle;
        }

        return $pageTitle.' — '.$siteTitle;
    }
}

if (! function_exists('bns_web_base_url')) {
    /**
     * Web-accessible base URL (works for subdirectory installs and live docroot).
     */
    function bns_web_base_url(): string
    {
        if (! app()->runningInConsole()) {
            try {
                $root = request()->root();
                if (is_string($root) && $root !== '') {
                    return rtrim($root, '/');
                }
            } catch (\Throwable) {
                // fall through to configured APP_URL
            }
        }

        return rtrim((string) config('app.url'), '/');
    }
}

if (! function_exists('bns_vasset')) {
    /**
     * Public asset URL with file-based cache busting (?v=mtime).
     */
    function bns_vasset(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');
        $fullPath = public_path($normalized);
        $url = bns_web_base_url().'/'.$normalized;

        if (is_file($fullPath)) {
            return $url.'?v='.filemtime($fullPath);
        }

        return $url;
    }
}

if (! function_exists('bns_public_media_url')) {
    /**
     * Resolve a stored media path to a browser URL only when the file exists.
     */
    function bns_public_media_url(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        if ((str_starts_with($path, 'assets/') || str_starts_with($path, 'uploads/'))
            && is_file(public_path($path))) {
            return bns_vasset($path);
        }

        if (is_file(public_path('storage/'.$path))) {
            return bns_vasset('storage/'.$path);
        }

        return null;
    }
}

if (! function_exists('bns_vurl')) {
    /**
     * Version a full public URL (e.g. storage symlink paths).
     */
    function bns_vurl(?string $url): string
    {
        if ($url === null || $url === '') {
            return '';
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return $url;
        }

        $normalized = ltrim($path, '/');
        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, 7);
        }

        $mediaUrl = bns_public_media_url($normalized);
        if ($mediaUrl !== null) {
            return $mediaUrl;
        }

        $fullPath = public_path($normalized);

        if (is_file($fullPath)) {
            $separator = str_contains($url, '?') ? '&' : '?';

            return $url.$separator.'v='.filemtime($fullPath);
        }

        return $url;
    }
}

if (! function_exists('bns_whatsapp_digits')) {
    /**
     * Normalize a phone number to WhatsApp-ready digits (country code included).
     */
    function bns_whatsapp_digits(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') {
            return '';
        }

        if (strlen($digits) === 10) {
            return '91'.$digits;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return '91'.substr($digits, 1);
        }

        return $digits;
    }
}

if (! function_exists('bns_whatsapp_link')) {
    /**
     * Build a WhatsApp click-to-chat URL with optional pre-filled message.
     */
    function bns_whatsapp_link(?string $message = null, ?string $phone = null): string
    {
        $digits = bns_whatsapp_digits($phone ?? (string) config('whatsapp.chat.number', ''));
        if ($digits === '') {
            return route('whatsapp.support');
        }

        $text = $message ?? (string) config('whatsapp.chat.float_message', '');
        $text = trim($text);
        $url = 'https://wa.me/'.$digits;

        if ($text !== '') {
            $url .= '?text='.rawurlencode($text);
        }

        return $url;
    }
}

if (! function_exists('bns_whatsapp_user_link')) {
    /**
     * Build a WhatsApp URL that opens with a pre-filled message for the user's mobile number.
     */
    function bns_whatsapp_user_link(?string $mobile, ?string $message = null): string
    {
        $digits = bns_whatsapp_digits($mobile);
        if ($digits === '') {
            return bns_whatsapp_link($message);
        }

        return bns_whatsapp_link($message, $digits);
    }
}

if (! function_exists('bns_intro_session_admission_url')) {
    /**
     * Direct link that opens the Introduction Session Admission form modal.
     */
    function bns_intro_session_admission_url(): string
    {
        return route('introduction-session.admission');
    }
}

if (! function_exists('bns_intro_session_allowed_numbers')) {
    /**
     * @return array<int, int>
     */
    function bns_intro_session_allowed_numbers(): array
    {
        $configured = config('intro_session_form.allowed_session_numbers', [1, 2, 3]);
        if (! is_array($configured) || $configured === []) {
            $configured = [1, 2, 3];
        }

        $numbers = array_map('intval', $configured);

        try {
            $fromDb = array_keys(app(\App\Services\IntroSessionScheduleService::class)->overridesBySession());
            $numbers = array_merge($numbers, array_map('intval', $fromDb));
        } catch (\Throwable) {
            // keep config numbers
        }

        $numbers = array_values(array_unique(array_filter($numbers, fn (int $n) => $n > 0)));
        sort($numbers);

        return $numbers !== [] ? $numbers : [1, 2, 3];
    }
}

if (! function_exists('bns_intro_session_selectable_numbers')) {
    /**
     * Upcoming introduction session numbers available on the admission form.
     * Example: Session 1 (12 Jul) past → returns [2, 3] for 08 Aug / 09 Aug.
     *
     * @return array<int, int>
     */
    function bns_intro_session_selectable_numbers(): array
    {
        $numbers = collect(bns_introduction_sessions(true))
            ->map(fn (array $event) => (int) ($event['session_number'] ?? 0))
            ->filter(fn (int $number) => $number > 0)
            ->values()
            ->all();

        return $numbers;
    }
}

if (! function_exists('bns_intro_session_number_for_date')) {
    /**
     * Resolve session number from a calendar date (Y-m-d or d/m/Y).
     * 2026-08-08 / 08/08/2026 → 2; 2026-08-09 / 09/08/2026 → 3; 2026-07-12 → 1.
     */
    function bns_intro_session_number_for_date(?string $date): ?int
    {
        $date = trim((string) $date);
        if ($date === '') {
            return null;
        }

        $normalized = null;
        try {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date) === 1) {
                $normalized = \Illuminate\Support\Carbon::createFromFormat('d/m/Y', $date, 'Asia/Kolkata')->toDateString();
            } else {
                $normalized = \Illuminate\Support\Carbon::parse($date, 'Asia/Kolkata')->toDateString();
            }
        } catch (\Throwable) {
            return null;
        }

        foreach (bns_introduction_sessions() as $event) {
            $startsAt = trim((string) ($event['starts_at'] ?? ''));
            if ($startsAt === '') {
                continue;
            }

            try {
                $eventDate = \Illuminate\Support\Carbon::parse($startsAt, 'Asia/Kolkata')->toDateString();
            } catch (\Throwable) {
                continue;
            }

            if ($eventDate === $normalized) {
                $number = (int) ($event['session_number'] ?? 0);

                return $number > 0 ? $number : null;
            }
        }

        return null;
    }
}

if (! function_exists('bns_introduction_sessions')) {
    /**
     * All configured introduction sessions, ordered by session_number.
     *
     * @param  bool  $upcomingOnly  When true, skip sessions whose end date has passed.
     * @return array<int, array<string, mixed>>
     */
    function bns_introduction_sessions(bool $upcomingOnly = false): array
    {
        $sessions = [];
        $scheduler = null;

        try {
            $scheduler = app(\App\Services\IntroSessionScheduleService::class);
        } catch (\Throwable) {
            $scheduler = null;
        }

        $template = null;
        foreach (config('events.events', []) as $event) {
            if (! is_array($event)) {
                continue;
            }

            if (($event['type'] ?? '') !== 'introduction') {
                continue;
            }

            $template = $event;

            if ($scheduler) {
                $event = $scheduler->applyToEvent($event);
            }

            if ($upcomingOnly && bns_event_has_passed($event)) {
                continue;
            }

            $number = (int) ($event['session_number'] ?? 0);
            if ($number > 0) {
                $sessions[$number] = $event;
            }
        }

        if ($scheduler) {
            foreach ($scheduler->overridesBySession() as $number => $override) {
                $number = (int) $number;
                if ($number <= 0 || isset($sessions[$number])) {
                    continue;
                }

                $event = is_array($template) ? $template : [
                    'type' => 'introduction',
                    'spotlight' => true,
                    'timezone' => 'Asia/Kolkata',
                    'venue' => 'Santacruz, Mumbai',
                    'category' => 'Introduction Session',
                    'cta' => ['label' => 'Register Now', 'route' => 'register'],
                ];
                $event['session_number'] = $number;
                $event['title'] = (string) ($override['title'] ?? 'Introduction Session');
                $event = $scheduler->applyToEvent($event);

                if ($upcomingOnly && bns_event_has_passed($event)) {
                    continue;
                }

                $sessions[$number] = $event;
            }
        }

        ksort($sessions);

        return array_values($sessions);
    }
}

if (! function_exists('bns_introduction_session')) {
    /**
     * @return array<string, mixed>|null
     */
    function bns_introduction_session(int $sessionNumber = 1): ?array
    {
        foreach (bns_introduction_sessions() as $event) {
            if ((int) ($event['session_number'] ?? 0) === $sessionNumber) {
                return $event;
            }
        }

        return null;
    }
}

if (! function_exists('bns_introduction_session_public_title')) {
    /**
     * Public-facing title for introduction sessions (no session number).
     *
     * @param  array<string, mixed>|null  $event
     */
    function bns_introduction_session_public_title(?array $event = null): string
    {
        if (is_array($event) && ($event['type'] ?? '') !== 'introduction' && ! empty($event['title'])) {
            return (string) $event['title'];
        }

        $category = is_array($event) ? trim((string) ($event['category'] ?? '')) : '';

        return $category !== '' ? $category : 'Introduction Session';
    }
}

if (! function_exists('bns_intro_session_label')) {
    /**
     * List/admin label for an introduction session.
     * Uses a custom title when set (e.g. Session 7 → "Introduction Session").
     *
     * @param  array<string, mixed>|null  $event
     */
    function bns_intro_session_label(int $number, ?array $event = null): string
    {
        if ($number <= 0) {
            return 'Introduction Session';
        }

        if (! is_array($event)) {
            try {
                $event = bns_introduction_session($number);
            } catch (\Throwable) {
                $event = null;
            }
        }

        $title = is_array($event) ? trim((string) ($event['title'] ?? '')) : '';
        if (
            $title !== ''
            && ! preg_match('/^Introduction Session\s+\d+$/i', $title)
            && strcasecmp($title, 'Session '.$number) !== 0
        ) {
            return $title;
        }

        return 'Session '.$number;
    }
}

if (! function_exists('bns_intro_session_venue_card')) {
    /**
     * Front-style venue / date / time card data for admin preview and emails.
     *
     * @param  array<string, mixed>|null  $event
     * @return array<string, mixed>
     */
    function bns_intro_session_venue_card(?array $event = null): array
    {
        $event = $event ?? [];

        return [
            'eyebrow' => 'Event Location',
            'headline' => 'Venue, Date, Time & Location',
            'intro' => 'We warmly invite you to attend the FREE Introduction Seminar of Business Navachar School (BNS).',
            'date' => (string) ($event['date'] ?? ''),
            'time' => (string) ($event['time'] ?? ''),
            'title' => (string) ($event['title'] ?? 'Introduction Session'),
            'address' => [
                'title' => 'Shri Vardhaman Sthanakwasi Jain Shravak Sangh',
                'lines' => [
                    'Smt. K. D. Mehta Dharmasthanak',
                    'M. D. Mehta Chowk',
                    'P. M. Road, Near Hi-Life Mall',
                    'Santacruz (West), Mumbai – 400054',
                ],
                'maps_url' => (string) ($event['maps_url'] ?? 'https://maps.app.goo.gl/Ve33LZjacUg2eB9H7'),
            ],
            'badge' => (string) ($event['seats'] ?? 'FREE Entry | Limited Seats | Registration Required'),
        ];
    }
}

if (! function_exists('bns_event_has_passed')) {
    /**
     * True when the event end (or event day) is already past in Asia/Kolkata.
     *
     * @param  array<string, mixed>  $event
     */
    function bns_event_has_passed(array $event): bool
    {
        $timezone = (string) ($event['timezone'] ?? config('app.timezone', 'Asia/Kolkata'));
        $now = \Illuminate\Support\Carbon::now($timezone);

        try {
            if (! empty($event['ends_at'])) {
                return \Illuminate\Support\Carbon::parse((string) $event['ends_at'], $timezone)->lt($now);
            }

            if (! empty($event['starts_at'])) {
                return \Illuminate\Support\Carbon::parse((string) $event['starts_at'], $timezone)->endOfDay()->lt($now);
            }

            $dateLabel = trim((string) ($event['date'] ?? ''));
            if ($dateLabel === '') {
                return false;
            }

            $datePart = trim(\Illuminate\Support\Str::before($dateLabel, '('));

            return \Illuminate\Support\Carbon::parse($datePart, $timezone)->endOfDay()->lt($now);
        } catch (\Throwable) {
            return false;
        }
    }
}

if (! function_exists('bns_intro_session_registration_count')) {
    /** Total Introduction Session Admission form submissions. */
    function bns_intro_session_registration_count(): int
    {
        return \App\Models\ContactInquiry::query()
            ->where(function ($query) {
                $query
                    ->where('form_source', 'intro-session-modal')
                    ->orWhere('message', 'like', 'Introduction session admission request%');
            })
            ->count();
    }
}

if (! function_exists('bns_intro_session_unique_mobile_count')) {
    /** Unique mobile numbers for Introduction Session Admission. */
    function bns_intro_session_unique_mobile_count(): int
    {
        return \App\Models\ContactInquiry::countUniqueIntroSessionMobiles();
    }
}

if (! function_exists('bns_intro_session_capacity')) {
    function bns_intro_session_capacity(): int
    {
        return (int) config('intro_session_form.unique_mobile_capacity', 166);
    }
}

if (! function_exists('bns_intro_session_mobile_map')) {
    /**
     * Map normalized intro-session mobiles to session number by ID ascending order.
     * First N unique mobiles → default session; remaining → overflow session.
     *
     * @return array<string, int>
     */
    function bns_intro_session_mobile_map(): array
    {
        return bns_reporting_session_number_map(onlyIntro: true);
    }
}

if (! function_exists('bns_reporting_session_mobile_map')) {
    /**
     * Map unique mobiles to Session 1 / 2 / 3 by stored choice or ID ascending order.
     * Without a stored choice: first 166 → default (Session 2); remaining → overflow (Session 3).
     *
     * @return array<string, array{session: int, source: string, id: int}>
     */
    function bns_reporting_session_mobile_map(bool $onlyIntro = false): array
    {
        $capacity = bns_intro_session_capacity();
        $allowed = bns_intro_session_allowed_numbers();
        $default = (int) config('intro_session_form.default_session_number', 2);
        try {
            $default = app(\App\Services\IntroSessionScheduleService::class)->defaultSessionNumber() ?: $default;
        } catch (\Throwable) {
            // keep config default
        }
        $overflow = (int) config('intro_session_form.overflow_session_number', 3);

        if (! in_array($default, $allowed, true)) {
            $default = $allowed[0] ?? 1;
        }
        if (! in_array($overflow, $allowed, true)) {
            $overflow = $allowed[count($allowed) - 1] ?? $default;
        }

        $query = $onlyIntro
            ? \App\Models\ContactInquiry::introSessionQuery()
            : \App\Models\ContactInquiry::primaryFormsQuery();

        return $query
            ->orderBy('id')
            ->get()
            ->groupBy(function (\App\Models\ContactInquiry $item) {
                $mobile = \App\Models\ContactInquiry::normalizeMobile($item->mobile);

                return $mobile !== '' ? $mobile : 'record-'.$item->id;
            })
            ->map(fn ($group) => $group->sortBy('id')->first())
            ->sortBy('id')
            ->values()
            ->mapWithKeys(function (\App\Models\ContactInquiry $item, int $index) use ($capacity, $allowed, $default, $overflow) {
                $mobile = \App\Models\ContactInquiry::normalizeMobile($item->mobile);
                $key = $mobile !== '' ? $mobile : 'record-'.$item->id;
                $source = $item->resolvedFormSource();
                $chosen = (int) ($item->intro_session_number ?? 0);
                $session = in_array($chosen, $allowed, true)
                    ? $chosen
                    : ($index < $capacity ? $default : $overflow);

                return [$key => [
                    'session' => $session,
                    'source' => $source,
                    'id' => (int) $item->id,
                ]];
            })
            ->all();
    }
}

if (! function_exists('bns_reporting_session_number_map')) {
    /**
     * @return array<string, int>
     */
    function bns_reporting_session_number_map(bool $onlyIntro = false): array
    {
        return collect(bns_reporting_session_mobile_map($onlyIntro))
            ->map(fn (array $row) => (int) $row['session'])
            ->all();
    }
}

if (! function_exists('bns_intro_session_number_for_mobile')) {
    function bns_intro_session_number_for_mobile(?string $mobile, ?array $mobileMap = null): ?int
    {
        $normalized = \App\Models\ContactInquiry::normalizeMobile($mobile);
        if ($normalized === '') {
            return null;
        }

        if ($mobileMap !== null) {
            $value = $mobileMap[$normalized] ?? null;
            if (is_array($value)) {
                return isset($value['session']) ? (int) $value['session'] : null;
            }

            return $value !== null ? (int) $value : null;
        }

        $map = bns_intro_session_mobile_map();
        $value = $map[$normalized] ?? null;
        if (is_array($value)) {
            return isset($value['session']) ? (int) $value['session'] : null;
        }

        return $value !== null ? (int) $value : null;
    }
}

if (! function_exists('bns_intro_session_number_for_count')) {
    /**
     * Resolve which intro session to show/assign.
     *
     * Pass the unique-mobile count BEFORE creating a new registration when
     * assigning a session to that registrant.
     */
    function bns_intro_session_number_for_count(?int $registeredCount = null): int
    {
        $forced = null;
        $defaultFromService = null;

        try {
            $scheduler = app(\App\Services\IntroSessionScheduleService::class);
            $forced = $scheduler->forcedSessionNumber();
            $defaultFromService = $scheduler->defaultSessionNumber();
        } catch (\Throwable) {
            $forced = config('intro_session_form.forced_session_number');
        }

        if ($forced !== null && $forced !== '' && (int) $forced > 0) {
            return (int) $forced;
        }

        $allowed = bns_intro_session_allowed_numbers();
        $default = (int) ($defaultFromService ?: config('intro_session_form.default_session_number', 2));
        $overflow = (int) config('intro_session_form.overflow_session_number', 3);

        if (! in_array($default, $allowed, true)) {
            $default = $allowed[0] ?? 1;
        }
        if (! in_array($overflow, $allowed, true)) {
            $overflow = $allowed[count($allowed) - 1] ?? $default;
        }

        $count = $registeredCount ?? bns_intro_session_unique_mobile_count();
        $capacity = bns_intro_session_capacity();

        if ($count >= $capacity) {
            return $overflow;
        }

        return $default;
    }
}

if (! function_exists('bns_session_attendance_breakdown')) {
    /**
     * Registered / present / absent unique mobiles for one introduction session.
     *
     * @return array{
     *     registered: int,
     *     present: int,
     *     absent: int,
     *     present_rows: \Illuminate\Support\Collection<int, \App\Models\ContactInquiry>,
     *     absent_rows: \Illuminate\Support\Collection<int, \App\Models\ContactInquiry>
     * }
     */
    function bns_session_attendance_breakdown(int $sessionNumber): array
    {
        $map = bns_reporting_session_mobile_map(onlyIntro: false);

        $ids = collect($map)
            ->filter(fn (array $row) => (int) ($row['session'] ?? 0) === $sessionNumber)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $registeredRows = $ids === []
            ? collect()
            : \App\Models\ContactInquiry::query()
                ->whereIn('id', $ids)
                ->orderBy('id')
                ->get();

        $attendedMobiles = \App\Models\SessionAttendance::query()
            ->where('session_number', $sessionNumber)
            ->get(['mobile'])
            ->map(fn (\App\Models\SessionAttendance $row) => \App\Models\ContactInquiry::normalizeMobile($row->mobile))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $attendedLookup = array_fill_keys($attendedMobiles, true);

        $presentRows = $registeredRows
            ->filter(function (\App\Models\ContactInquiry $row) use ($attendedLookup) {
                $mobile = \App\Models\ContactInquiry::normalizeMobile($row->mobile);

                return $mobile !== '' && isset($attendedLookup[$mobile]);
            })
            ->values();

        $absentRows = $registeredRows
            ->filter(function (\App\Models\ContactInquiry $row) use ($attendedLookup) {
                $mobile = \App\Models\ContactInquiry::normalizeMobile($row->mobile);

                return $mobile === '' || ! isset($attendedLookup[$mobile]);
            })
            ->values();

        return [
            'registered' => $registeredRows->count(),
            'present' => $presentRows->count(),
            'absent' => $absentRows->count(),
            'present_rows' => $presentRows,
            'absent_rows' => $absentRows,
        ];
    }
}

if (! function_exists('bns_next_introduction_session')) {
    /**
     * Next upcoming introduction session (skips dates that have already passed).
     *
     * @return array<string, mixed>|null
     */
    function bns_next_introduction_session(): ?array
    {
        $upcoming = bns_introduction_sessions(true);

        usort($upcoming, static function (array $a, array $b): int {
            $aKey = (string) ($a['starts_at'] ?? $a['date'] ?? '');
            $bKey = (string) ($b['starts_at'] ?? $b['date'] ?? '');

            return strcmp($aKey, $bKey);
        });

        return $upcoming[0] ?? null;
    }
}

if (! function_exists('bns_first_introduction_session')) {
    /**
     * Session shown on public intro/admission popups: next upcoming intro session.
     *
     * @return array<string, mixed>|null
     */
    function bns_first_introduction_session(): ?array
    {
        return bns_next_introduction_session()
            ?? bns_introduction_session(bns_intro_session_number_for_count());
    }
}

if (! function_exists('bns_attendance_enabled')) {
    /**
     * Home sticky + reporting Attendance tab visibility (yes/true/1 = show).
     */
    function bns_attendance_enabled(): bool
    {
        $value = config('attendance.enabled', 'yes');

        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
    }
}

if (! function_exists('bns_book_your_spot_url')) {
    /**
     * Direct link that opens the Book Your Spot Now admission modal.
     */
    function bns_book_your_spot_url(): string
    {
        return route('book-your-spot');
    }
}

if (! function_exists('bns_order_program_boxes')) {
    /**
     * Sort program explore boxes into the standard BNS sequence.
     */
    function bns_order_program_boxes(array $boxes): array
    {
        $order = config('audience_program_explore_box_order', []);
        $orderMap = array_flip($order);
        $labels = [
            'why_business_education' => 'BNS in Education',
            'program_structure' => 'Syllabus',
            'prosperity_mission' => 'India Prosperity Vision',
        ];

        return collect($boxes)
            ->map(function (array $box) use ($labels) {
                $key = $box['key'] ?? '';
                if (isset($labels[$key])) {
                    $box['label'] = $labels[$key];
                }

                return $box;
            })
            ->sortBy(function (array $box, int $index) use ($orderMap) {
                $key = $box['key'] ?? $box['modal'] ?? '';

                return $orderMap[$key] ?? (1000 + $index);
            })
            ->values()
            ->all();
    }
}

if (! function_exists('bns_youtube_video_id')) {
    function bns_youtube_video_id(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:shorts\/|watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}

if (! function_exists('bns_youtube_embed_url')) {
    function bns_youtube_embed_url(?string $url, bool $autoplay = false): string
    {
        $id = bns_youtube_video_id($url);
        if (! $id) {
            return '';
        }

        $params = [
            'rel' => '0',
            'modestbranding' => '1',
            'playsinline' => '1',
            'enablejsapi' => '1',
        ];

        if ($autoplay) {
            $params['autoplay'] = '1';
            $params['mute'] = '1';
        }

        if (app()->bound('request')) {
            $params['origin'] = request()->getSchemeAndHttpHost();
        }

        return 'https://www.youtube.com/embed/'.$id.'?'.http_build_query($params);
    }
}

if (! function_exists('bns_youtube_watch_url')) {
    function bns_youtube_watch_url(?string $url): string
    {
        $id = bns_youtube_video_id($url);
        if (! $id) {
            return '';
        }

        if ($url !== null && str_contains($url, '/shorts/')) {
            return 'https://www.youtube.com/shorts/'.$id;
        }

        return 'https://www.youtube.com/watch?v='.$id;
    }
}

if (! function_exists('bns_rich_text')) {
    /**
     * Render trusted config/CMS copy that may include emphasis markup.
     * Auto-wraps important phrases in <strong class="bns-em"> for consistent site-wide styling.
     *
     * @param  bool  $autoEmphasize  When false, only sanitize — do not auto-wrap phrases.
     */
    function bns_rich_text(?string $text, bool $autoEmphasize = true): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $text = strip_tags($text, '<strong><b><em><i><br><span>');
        // Normalize whitespace so emphasis never creates visual gaps.
        $text = preg_replace('/[ \t\x{00A0}]+/u', ' ', $text) ?? $text;
        $text = trim($text);

        if (! $autoEmphasize) {
            return $text;
        }

        $phrases = config('bns_emphasis.phrases', []);
        if ($phrases === []) {
            return $text;
        }

        usort($phrases, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        foreach ($phrases as $phrase) {
            if ($phrase === '' || stripos($text, $phrase) === false) {
                continue;
            }

            // Skip when the phrase is already inside a strong/b tag.
            if (preg_match('/<(?:strong|b)[^>]*>[^<]*'.preg_quote($phrase, '/').'[^<]*<\/(?:strong|b)>/iu', $text)) {
                continue;
            }

            $text = preg_replace(
                '/'.preg_quote($phrase, '/').'/iu',
                '<strong class="bns-em">$0</strong>',
                $text
            ) ?? $text;
        }

        // Flatten accidental nested <strong> wrappers (e.g. lead already wrapped in template).
        do {
            $cleaned = preg_replace(
                '/<(strong|b)(\s[^>]*)?>(\s*)<(strong|b)(\s[^>]*)?>(.*?)<\/\4>(\s*)<\/\1>/iu',
                '<strong class="bns-em">$3$6$7</strong>',
                $text
            );
            if ($cleaned === null || $cleaned === $text) {
                break;
            }
            $text = $cleaned;
        } while (true);

        return $text;
    }
}

if (! function_exists('bns_point_html')) {
    /**
     * Render a star/circle list item: optional lead + text, with clean emphasis (no spacing gaps).
     *
     * @param  array<string, mixed>|string|null  $item
     */
    function bns_point_html(array|string|null $item): string
    {
        if ($item === null || $item === '') {
            return '';
        }

        if (! is_array($item)) {
            return bns_rich_text((string) $item);
        }

        $lead = trim(strip_tags((string) ($item['lead'] ?? '')));
        $text = $item['text'] ?? '';

        if ($lead !== '') {
            $html = '<strong class="bns-em">'.e($lead).'</strong>';
            if (is_string($text) && trim(strip_tags($text)) !== '') {
                $html .= ' <span>'.bns_rich_text($text).'</span>';
            }

            return $html;
        }

        return bns_rich_text(is_string($text) ? $text : '');
    }
}

if (! function_exists('bns_youtube_thumbnail_url')) {
    function bns_youtube_thumbnail_url(?string $url): string
    {
        $id = bns_youtube_video_id($url);
        if (! $id) {
            return '';
        }

        if ($url !== null && str_contains($url, '/shorts/')) {
            return 'https://i.ytimg.com/vi/'.$id.'/oardefault.jpg';
        }

        return 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg';
    }
}

if (! function_exists('bns_message_email_templates')) {
    /**
     * All sendable email templates for Session Email Sending.
     * Includes welcome confirmation + every Communication Sequence message.
     *
     * @return array<int, array{id: string, number: int, label: string, stage: string, title: string, type: string}>
     */
    function bns_message_email_templates(): array
    {
        $templates = [[
            'id' => 'welcome-confirmation',
            'label' => 'Welcome Confirmation + Calendar (Default)',
            'stage' => 'System',
            'title' => 'Welcome Confirmation + Calendar',
            'type' => 'welcome',
        ]];

        foreach (config('messages.sections', []) as $section) {
            if (! is_array($section)) {
                continue;
            }

            $stageTitle = (string) ($section['short_title'] ?? $section['title'] ?? 'Stage');
            foreach ($section['items'] ?? [] as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $id = trim((string) ($item['id'] ?? ''));
                $title = trim((string) ($item['title'] ?? ''));
                if ($id === '' || $title === '') {
                    continue;
                }

                $templates[] = [
                    'id' => $id,
                    'label' => $stageTitle.' — '.$title,
                    'stage' => $stageTitle,
                    'title' => $title,
                    'type' => 'sequence',
                ];
            }
        }

        foreach ($templates as $index => &$template) {
            $template['number'] = $index + 1;
            $template['label'] = $template['number'].'. '.$template['label'];
        }
        unset($template);

        return $templates;
    }
}

if (! function_exists('bns_intro_session_display_formats')) {
    /**
     * Human-readable date/time variants for one introduction session.
     *
     * @param  array<string, mixed>  $session
     * @return array{
     *     number:int,
     *     label:string,
     *     date:string,
     *     date_long:string,
     *     date_medium:string,
     *     date_ordinal:string,
     *     date_compact:string,
     *     time:string,
     *     time_to:string,
     *     time_short:string,
     *     report:string
     * }
     */
    function bns_intro_session_display_formats(array $session): array
    {
        $number = (int) ($session['session_number'] ?? 0);
        $timezone = (string) ($session['timezone'] ?? 'Asia/Kolkata');
        $startsAt = null;
        $endsAt = null;

        try {
            if (! empty($session['starts_at'])) {
                $startsAt = \Illuminate\Support\Carbon::parse((string) $session['starts_at'], $timezone);
            }
        } catch (\Throwable) {
            $startsAt = null;
        }

        try {
            if (! empty($session['ends_at'])) {
                $endsAt = \Illuminate\Support\Carbon::parse((string) $session['ends_at'], $timezone);
            }
        } catch (\Throwable) {
            $endsAt = null;
        }

        if (! $startsAt) {
            try {
                $datePart = trim(\Illuminate\Support\Str::before((string) ($session['date'] ?? ''), '('));
                if ($datePart !== '') {
                    $startsAt = \Illuminate\Support\Carbon::parse($datePart, $timezone)->setTime(14, 30);
                }
            } catch (\Throwable) {
                $startsAt = null;
            }
        }

        if ($startsAt && ! $endsAt) {
            $endsAt = $startsAt->copy()->addHours(2);
        }

        $ordinal = static function (int $day): string {
            if ($day % 100 >= 11 && $day % 100 <= 13) {
                return $day.'th';
            }

            return $day.match ($day % 10) {
                1 => 'st',
                2 => 'nd',
                3 => 'rd',
                default => 'th',
            };
        };

        $dateLabel = trim((string) ($session['date'] ?? ''));
        $timeLabel = trim((string) ($session['time'] ?? ''));

        if ($startsAt) {
            $dateLabel = $dateLabel !== '' ? $dateLabel : $startsAt->format('d F Y').' ('.$startsAt->format('l').')';
            $dateLong = $startsAt->format('l').', '.$startsAt->format('d F Y');
            $dateMedium = $startsAt->format('d F Y');
            $dateOrdinal = $ordinal((int) $startsAt->format('j')).' '.$startsAt->format('F Y');
            $dateCompact = $startsAt->format('D j M Y');
            $timeTo = $endsAt
                ? $startsAt->format('g:i A').' to '.$endsAt->format('g:i A')
                : $startsAt->format('g:i A');
            $timeDash = $endsAt
                ? $startsAt->format('g:i A').' – '.$endsAt->format('g:i A')
                : $startsAt->format('g:i A');
            $timeShort = $endsAt
                ? $startsAt->format('g:i').'–'.$endsAt->format('g:i A')
                : $startsAt->format('g:i A');
            $report = $startsAt->copy()->subMinutes(15)->format('g:i A');
            $timeLabel = $timeLabel !== '' ? $timeLabel : $timeDash;
        } else {
            $dateLong = $dateLabel;
            $dateMedium = trim(\Illuminate\Support\Str::before($dateLabel, '(')) ?: $dateLabel;
            $dateOrdinal = $dateMedium;
            $dateCompact = $dateMedium;
            $timeTo = str_replace([' – ', ' — ', ' - '], ' to ', $timeLabel);
            $timeDash = $timeLabel !== '' ? $timeLabel : $timeTo;
            $timeShort = str_replace([' PM', ' AM'], '', str_replace([' to ', ' – '], '–', $timeTo));
            $report = '';
        }

        return [
            'number' => $number,
            'label' => bns_intro_session_label($number, is_array($session) ? $session : null),
            'date' => $dateLabel,
            'date_long' => $dateLong,
            'date_medium' => $dateMedium,
            'date_ordinal' => $dateOrdinal,
            'date_compact' => $dateCompact,
            'time' => $timeLabel,
            'time_to' => $timeTo,
            'time_short' => $timeShort,
            'report' => $report,
        ];
    }
}

if (! function_exists('bns_intro_session_replacement_map')) {
    /**
     * Map every known intro-session date/time/label to the live active session.
     *
     * @return array<string, string>
     */
    function bns_intro_session_replacement_map(?array $target = null): array
    {
        $target ??= bns_first_introduction_session();
        if (! is_array($target) || $target === []) {
            return [];
        }

        $live = bns_intro_session_display_formats($target);
        $map = [];

        $add = static function (string $from, string $to) use (&$map): void {
            $from = trim($from);
            $to = trim($to);
            if ($from === '' || $to === '' || strcasecmp($from, $to) === 0) {
                return;
            }
            $map[$from] = $to;
        };

        $sources = [];
        foreach (config('events.events', []) as $event) {
            if (is_array($event) && ($event['type'] ?? '') === 'introduction') {
                $sources[] = $event;
            }
        }
        foreach (bns_introduction_sessions() as $event) {
            $sources[] = $event;
        }

        foreach ($sources as $event) {
            if (! is_array($event)) {
                continue;
            }

            $old = bns_intro_session_display_formats($event);
            if ($old['number'] <= 0) {
                continue;
            }

            $add('Introduction Session '.$old['number'], $live['label']);
            $add('Session '.$old['number'], $live['label']);
            $add((string) ($event['title'] ?? ''), $live['label']);

            $add($old['date'], $live['date']);
            $add($old['date_long'], $live['date_long']);
            $add($old['date_medium'], $live['date_medium']);
            $add($old['date_ordinal'], $live['date_ordinal']);
            $add($old['date_compact'], $live['date_compact']);
            $add((string) ($event['date'] ?? ''), $live['date']);

            $eventTime = (string) ($event['time'] ?? '');
            $add($old['time'], $live['time']);
            $add($old['time_to'], $live['time_to']);
            $add($old['time_short'], $live['time_short']);
            $add($eventTime, $live['time']);
            $add(str_replace([' – ', ' — ', ' - '], ' to ', $eventTime), $live['time_to']);
            $add(str_replace([' to ', ' — ', ' - '], ' – ', $eventTime), $live['time']);

            if ($old['report'] !== '' && $live['report'] !== '') {
                $add($old['report'], $live['report']);
            }
        }

        $add('{{session_label}}', $live['label']);
        $add('{{session_number}}', (string) $live['number']);
        $add('{{session_date}}', $live['date']);
        $add('{{session_date_long}}', $live['date_long']);
        $add('{{session_time}}', $live['time_to']);
        $add('{{session_report}}', $live['report']);

        uksort($map, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        return $map;
    }
}

if (! function_exists('bns_apply_live_intro_session')) {
    /**
     * Recursively rewrite hardcoded intro-session date/time text to the live admin schedule.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function bns_apply_live_intro_session(mixed $value, ?array $session = null): mixed
    {
        static $mapCache = null;
        static $mapSessionKey = null;

        $session ??= bns_first_introduction_session();
        $sessionKey = is_array($session)
            ? ((string) ($session['session_number'] ?? '').'|'.(string) ($session['starts_at'] ?? '').'|'.(string) ($session['date'] ?? ''))
            : 'none';

        if ($mapCache === null || $mapSessionKey !== $sessionKey) {
            $mapCache = bns_intro_session_replacement_map(is_array($session) ? $session : null);
            $mapSessionKey = $sessionKey;
        }

        if ($mapCache === []) {
            return $value;
        }

        if (is_string($value)) {
            return str_replace(array_keys($mapCache), array_values($mapCache), $value);
        }

        if (is_array($value)) {
            foreach ($value as $key => $child) {
                $value[$key] = bns_apply_live_intro_session($child, $session);
            }
        }

        return $value;
    }
}

if (! function_exists('bns_sync_message_session_fields')) {
    /**
     * Force structured message payload date/time fields to the live session.
     *
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>  $session
     * @return array<string, mixed>
     */
    function bns_sync_message_session_fields(array $item, array $session): array
    {
        $live = bns_intro_session_display_formats($session);

        $patchNode = function (&$node) use (&$patchNode, $live, $session): void {
            if (! is_array($node)) {
                return;
            }

            foreach (['date', 'date_label', 'session_date'] as $key) {
                if (array_key_exists($key, $node) && is_string($node[$key]) && trim($node[$key]) !== '') {
                    $node[$key] = str_contains(strtolower($node[$key]), 'session')
                        ? $live['label'].': '.$live['date_medium']
                        : (str_contains($node[$key], ',') ? $live['date_long'] : $live['date_medium']);
                }
            }

            foreach (['time', 'time_label', 'session_time', 'seminar_time'] as $key) {
                if (array_key_exists($key, $node) && is_string($node[$key]) && trim($node[$key]) !== '') {
                    $node[$key] = str_contains(strtolower($node[$key]), 'session')
                        ? $live['label'].': '.$live['time_to']
                        : $live['time_to'];
                }
            }

            foreach (['reporting', 'reporting_time', 'report_time'] as $key) {
                if (array_key_exists($key, $node) && is_string($node[$key]) && $live['report'] !== '') {
                    $node[$key] = str_contains(strtolower($node[$key]), 'session')
                        ? $live['label'].': '.$live['report']
                        : $live['report'];
                }
            }

            foreach (['label', 'session_label', 'session_title'] as $key) {
                if (array_key_exists($key, $node) && is_string($node[$key])) {
                    $value = trim($node[$key]);
                    if (preg_match('/^session\s*\d+$/i', $value) === 1
                        || preg_match('/^introduction session\s*\d+$/i', $value) === 1
                    ) {
                        $node[$key] = $live['label'];
                    }
                }
            }

            if (isset($node['sessions']) && is_array($node['sessions'])) {
                $upcoming = bns_introduction_sessions(true);
                if ($upcoming !== []) {
                    $node['sessions'] = array_map(static function (array $event) {
                        $fmt = bns_intro_session_display_formats($event);

                        return [
                            'label' => $fmt['label'],
                            'date' => $fmt['date_long'],
                            'time' => $fmt['time_to'],
                            'title' => $fmt['label'],
                            'session_number' => $fmt['number'],
                        ];
                    }, $upcoming);
                } else {
                    $node['sessions'] = [[
                        'label' => $live['label'],
                        'date' => $live['date_long'],
                        'time' => $live['time_to'],
                        'title' => $live['label'],
                        'session_number' => $live['number'],
                    ]];
                }
            }

            foreach ($node as &$child) {
                if (is_array($child)) {
                    $patchNode($child);
                }
            }
            unset($child);
        };

        $patchNode($item);

        return $item;
    }
}

if (! function_exists('bns_enrich_message_item')) {
    /**
     * Resolve routes / session placeholders so email bodies match the web message UI.
     *
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>|null  $session
     * @return array<string, mixed>
     */
    function bns_enrich_message_item(array $item, ?array $session = null): array
    {
        $session ??= function_exists('bns_first_introduction_session')
            ? bns_first_introduction_session()
            : null;

        // Rewrite hardcoded Session date/time text across WhatsApp + email payloads.
        if (is_array($session) && $session !== []) {
            $item = bns_apply_live_intro_session($item, $session);
            $item = bns_sync_message_session_fields($item, $session);
        }

        if (! empty($item['use_next_session']) && is_array($session)) {
            $extra = array_filter([
                'Date: '.($session['date'] ?? ''),
                'Time: '.($session['time'] ?? ''),
                'Venue: '.($session['venue'] ?? ''),
                'Address: '.($session['location_full'] ?? ''),
            ]);

            $item['body'] = array_values(array_merge($item['body'] ?? [], $extra));

            if (! empty($session['maps_url'])) {
                $item['links'] = array_values(array_merge($item['links'] ?? [], [[
                    'label' => 'Open GPS / Google Maps',
                    'url' => $session['maps_url'],
                    'external' => true,
                ]]));
            }
        }

        if (! empty($item['cta']['route']) && empty($item['cta']['url'])) {
            try {
                $item['cta']['url'] = route($item['cta']['route'], $item['cta']['params'] ?? []);
            } catch (\Throwable) {
                $item['cta']['url'] = url('/');
            }
        }

        if (! empty($item['links']) && is_array($item['links'])) {
            foreach ($item['links'] as $i => $link) {
                if (! is_array($link)) {
                    continue;
                }
                if (! empty($link['route']) && empty($link['url'])) {
                    try {
                        $item['links'][$i]['url'] = route($link['route'], $link['params'] ?? []);
                    } catch (\Throwable) {
                        $item['links'][$i]['url'] = url('/');
                    }
                }
            }
        }

        return $item;
    }
}

if (! function_exists('bns_message_email_template')) {
    /**
     * @return array{id: string, label: string, stage: string, title: string, type: string, layout?: string, whatsapp?: string, body_html?: string, rich_html?: string}|null
     */
    function bns_message_email_template(string $templateId): ?array
    {
        $templateId = trim($templateId);
        if ($templateId === '') {
            return null;
        }

        foreach (bns_message_email_templates() as $template) {
            if ($template['id'] !== $templateId) {
                continue;
            }

            if (($template['type'] ?? '') === 'welcome') {
                return $template;
            }

            foreach (config('messages.sections', []) as $section) {
                if (! is_array($section)) {
                    continue;
                }

                foreach ($section['items'] ?? [] as $item) {
                    if (! is_array($item) || (string) ($item['id'] ?? '') !== $templateId) {
                        continue;
                    }

                    $item = bns_enrich_message_item($item);

                    $whatsapp = trim((string) ($item['whatsapp'] ?? ''));
                    if ($whatsapp === '' && ! empty($item['body']) && is_array($item['body'])) {
                        $whatsapp = collect($item['body'])
                            ->map(fn ($line) => trim(strip_tags((string) $line)))
                            ->filter()
                            ->implode("\n\n");
                    }

                    $richHtml = '';
                    try {
                        $richHtml = (new \App\Support\MessageEmailHtmlBuilder)->render($item);
                    } catch (\Throwable $e) {
                        report($e);
                        $richHtml = '';
                    }

                    $bodyHtml = $whatsapp !== '' ? bns_whatsapp_text_to_email_html($whatsapp) : '';

                    if ($richHtml === '' && $bodyHtml === '') {
                        $bodyHtml = '<p style="margin:0;font-size:15px;line-height:1.7;color:#334155;">'.e((string) ($item['title'] ?? 'BNS Message')).'</p>';
                    }

                    return [
                        ...$template,
                        'layout' => (string) ($item['layout'] ?? ''),
                        'whatsapp' => $whatsapp,
                        'body_html' => $bodyHtml,
                        'rich_html' => $richHtml,
                    ];
                }
            }

            return $template;
        }

        return bns_mail_portal_email_template($templateId);
    }
}

if (! function_exists('bns_mail_portal_email_templates')) {
    /**
     * Sendable templates for /mail portal (WhatsApp/SMS + Email list).
     *
     * @return array<int, array{id: string, number: int, label: string, stage: string, title: string, type: string}>
     */
    function bns_mail_portal_email_templates(): array
    {
        $templates = [];

        foreach (config('mail_messages.sections', []) as $section) {
            if (! is_array($section)) {
                continue;
            }

            $stageTitle = (string) ($section['short_title'] ?? $section['title'] ?? 'Mail');
            foreach ($section['items'] ?? [] as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $id = trim((string) ($item['id'] ?? ''));
                $title = trim((string) ($item['title'] ?? ''));
                if ($id === '' || $title === '') {
                    continue;
                }

                $templates[] = [
                    'id' => $id,
                    'label' => $stageTitle.' — '.$title,
                    'stage' => $stageTitle,
                    'title' => $title,
                    'type' => 'mail_portal',
                ];
            }
        }

        foreach ($templates as $index => &$template) {
            $template['number'] = $index + 1;
            $template['label'] = $template['number'].'. '.$template['label'];
        }
        unset($template);

        return $templates;
    }
}

if (! function_exists('bns_mail_portal_email_template')) {
    /**
     * @return array{id: string, label: string, stage: string, title: string, type: string, layout?: string, whatsapp?: string, body_html?: string, rich_html?: string}|null
     */
    function bns_mail_portal_email_template(string $templateId): ?array
    {
        $templateId = trim($templateId);
        if ($templateId === '') {
            return null;
        }

        foreach (bns_mail_portal_email_templates() as $template) {
            if ($template['id'] !== $templateId) {
                continue;
            }

            foreach (config('mail_messages.sections', []) as $section) {
                if (! is_array($section)) {
                    continue;
                }

                foreach ($section['items'] ?? [] as $item) {
                    if (! is_array($item) || (string) ($item['id'] ?? '') !== $templateId) {
                        continue;
                    }

                    $item = bns_enrich_message_item($item);

                    $whatsapp = trim((string) ($item['whatsapp'] ?? ''));
                    if ($whatsapp === '' && ! empty($item['body']) && is_array($item['body'])) {
                        $whatsapp = collect($item['body'])
                            ->map(fn ($line) => trim(strip_tags((string) $line)))
                            ->filter()
                            ->implode("\n\n");
                    }

                    $richHtml = '';
                    try {
                        $richHtml = (new \App\Support\MessageEmailHtmlBuilder)->render($item);
                    } catch (\Throwable $e) {
                        report($e);
                        $richHtml = '';
                    }

                    $bodyHtml = $whatsapp !== '' ? bns_whatsapp_text_to_email_html($whatsapp) : '';

                    if ($richHtml === '' && $bodyHtml === '') {
                        $bodyHtml = '<p style="margin:0;font-size:15px;line-height:1.7;color:#334155;">'.e((string) ($item['title'] ?? 'BNS Mail')).'</p>';
                    }

                    return [
                        ...$template,
                        'layout' => (string) ($item['layout'] ?? ''),
                        'whatsapp' => $whatsapp,
                        'body_html' => $bodyHtml,
                        'rich_html' => $richHtml,
                    ];
                }
            }

            return $template;
        }

        return null;
    }
}

if (! function_exists('bns_build_message_catalog')) {
    /**
     * Build the front message-viewer catalog array from config sections.
     *
     * @param  array<string, mixed>  $sections
     * @return list<array<string, mixed>>
     */
    function bns_build_message_catalog(array $sections): array
    {
        $messageCatalog = [];

        foreach ($sections as $sectionKey => $section) {
            if (! is_array($section)) {
                continue;
            }

            $items = array_values($section['items'] ?? []);
            foreach ($items as $itemIndex => $item) {
                if (! is_array($item)) {
                    continue;
                }

                $plain = ! empty($item['whatsapp'])
                    ? trim((string) $item['whatsapp'])
                    : collect($item['body'] ?? [])
                        ->map(fn ($line) => trim(strip_tags((string) $line)))
                        ->filter()
                        ->implode("\n\n");

                $messageCatalog[] = [
                    'section' => $sectionKey,
                    'section_title' => $section['title'] ?? 'Message',
                    'index' => $itemIndex,
                    'total' => count($items),
                    'id' => $item['id'] ?? ($sectionKey.'-'.$itemIndex),
                    'title' => $item['title'] ?? 'Message',
                    'layout' => $item['layout'] ?? 'default',
                    'image' => ! empty($item['image']) ? bns_vasset($item['image']) : null,
                    'body' => array_values(array_map(
                        fn ($line) => bns_rich_text((string) $line),
                        $item['body'] ?? []
                    )),
                    'plain' => $plain,
                    'promo' => $item['promo'] ?? null,
                    'about' => $item['about'] ?? null,
                    'vision' => $item['vision'] ?? null,
                    'pitch' => $item['pitch'] ?? null,
                    'reels' => $item['reels'] ?? null,
                    'journey' => $item['journey'] ?? null,
                    'benefits' => $item['benefits'] ?? null,
                    'highlights' => $item['highlights'] ?? null,
                    'bring' => $item['bring'] ?? null,
                    'countdown' => $item['countdown'] ?? null,
                    'confirm' => $item['confirm'] ?? null,
                    'thanks' => $item['thanks'] ?? null,
                    'savedate' => $item['savedate'] ?? null,
                    'calreminder' => $item['calreminder'] ?? null,
                    'wachannel' => $item['wachannel'] ?? null,
                    'venue' => $item['venue'] ?? null,
                    'dress' => $item['dress'] ?? null,
                    'bizcard' => $item['bizcard'] ?? null,
                    'reporting' => $item['reporting'] ?? null,
                    'surprise' => $item['surprise'] ?? null,
                    'tomorrow' => $item['tomorrow'] ?? null,
                    'checklist' => $item['checklist'] ?? null,
                    'founder' => $item['founder'] ?? null,
                    'today' => $item['today'] ?? null,
                    'reminder' => $item['reminder'] ?? null,
                    'welcome' => $item['welcome'] ?? null,
                    'venuegps' => $item['venuegps'] ?? null,
                    'welcomereg' => $item['welcomereg'] ?? null,
                    'attendance' => $item['attendance'] ?? null,
                    'instructions' => $item['instructions'] ?? null,
                    'admitcounter' => $item['admitcounter'] ?? null,
                    'scholarship' => $item['scholarship'] ?? null,
                    'usefullinks' => $item['usefullinks'] ?? null,
                    'semthanks' => $item['semthanks'] ?? null,
                    'photogallery' => $item['photogallery'] ?? null,
                    'introsession' => $item['introsession'] ?? null,
                    'syllabus' => $item['syllabus'] ?? null,
                    'admitreminder' => $item['admitreminder'] ?? null,
                    'paynow' => $item['paynow'] ?? null,
                    'firstbatch' => $item['firstbatch'] ?? null,
                    'faq' => $item['faq'] ?? null,
                    'bnsfamily' => $item['bnsfamily'] ?? null,
                    'founderwelcome' => $item['founderwelcome'] ?? null,
                    'coach' => $item['coach'] ?? null,
                    'links' => array_values(array_map(function ($link) {
                        return [
                            'label' => $link['label'] ?? 'Open link',
                            'url' => $link['url'] ?? '#',
                            'external' => ! empty($link['external']),
                        ];
                    }, $item['links'] ?? [])),
                    'cta' => ! empty($item['cta']['url']) ? [
                        'label' => $item['cta']['label'] ?? 'Continue',
                        'url' => $item['cta']['url'],
                    ] : null,
                ];
            }
        }

        return $messageCatalog;
    }
}

if (! function_exists('bns_whatsapp_text_to_email_html')) {
    /** Convert WhatsApp-style plain text (*bold*, links, newlines) into safe email HTML. */
    function bns_whatsapp_text_to_email_html(?string $text): string
    {
        $text = trim((string) $text);
        if ($text === '') {
            return '';
        }

        $escaped = e($text);
        $escaped = preg_replace(
            '/(https?:\/\/[^\s<]+)/i',
            '<a href="$1" style="color:#fd6e01;text-decoration:underline;word-break:break-all;">$1</a>',
            $escaped
        ) ?? $escaped;
        $escaped = preg_replace('/\*(.+?)\*/s', '<strong style="color:#0a1d37;">$1</strong>', $escaped) ?? $escaped;
        $escaped = preg_replace('/^━+$/m', '<hr style="border:none;border-top:1px solid #e5e7eb;margin:14px 0;">', $escaped) ?? $escaped;
        $escaped = preg_replace('/^\.{3,}$/m', '<hr style="border:none;border-top:1px dashed #e5e7eb;margin:12px 0;">', $escaped) ?? $escaped;
        $escaped = preg_replace("/\n{2,}/", '</p><p style="margin:0 0 14px;font-size:15px;line-height:1.7;color:#334155;">', $escaped) ?? $escaped;
        $escaped = nl2br($escaped);

        return '<p style="margin:0 0 14px;font-size:15px;line-height:1.7;color:#334155;">'.$escaped.'</p>';
    }
}
