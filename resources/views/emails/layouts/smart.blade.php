{{--
  Universal front-style email layout.
  Renders ALL common structured fields so every template sends full content.
--}}
@php
    $d = is_array($data ?? null) ? $data : [];
    $item = is_array($item ?? null) ? $item : [];
    $title = (string) ($item['title'] ?? 'BNS Message');

    $eyebrow = (string) ($d['eyebrow'] ?? $d['badge_label'] ?? $title);
    // Prefer real headline over brand (brand often exists as a footer signature field).
    $headline = (string) ($d['headline'] ?? $d['brand'] ?? $title);

    $taglineRaw = $d['tagline'] ?? null;
    if (is_array($taglineRaw)) {
        $mottoFromTagline = array_values(array_filter(array_map(
            static fn ($line) => is_array($line)
                ? trim((string) (($line['icon'] ?? '').' '.($line['text'] ?? '')))
                : trim((string) $line),
            $taglineRaw
        )));
        $taglineRaw = '';
    } else {
        $mottoFromTagline = [];
        $taglineRaw = is_string($taglineRaw) ? $taglineRaw : '';
    }

    $subSource = $taglineRaw !== '' ? $taglineRaw : ($d['hero_sub'] ?? $d['headline_sub'] ?? '');
    $sub = is_scalar($subSource) ? (string) $subSource : '';
    if ($sub === $headline) {
        $sub = '';
    }
    if ($headline === ($d['brand'] ?? '') && ! empty($d['headline']) && is_string($d['headline']) && $d['headline'] !== $headline) {
        $sub = (string) $d['headline'];
    }

    $textBlocks = [];
    foreach ([
        'greeting', 'thanks', 'intro', 'designed', 'clarity', 'request', 'opportunity',
        'question', 'punch', 'welcome', 'status', 'confirmed', 'congrats',
        'what_intro', 'mission', 'vision', 'dress', 'dress_note', 'invite', 'invite_intro',
        'calendar_note', 'help', 'closing_thanks', 'closing', 'reserved', 'urgency',
        'complete', 'bot_intro', 'website_intro', 'highlights_note', 'highlights_cta',
        'seats', 'seats_note', 'travel', 'note', 'highlight', 'hook', 'answer',
        'glimpse', 'journey', 'thanks_note', 'assist', 'safe', 'final', 'final_note',
        'appreciation', 'growth', 'see_you', 'belief', 'delighted', 'community', 'founding',
        'commit', 'commitment', 'family', 'excited', 'together', 'surprise', 'teaser',
        'reveal', 'special', 'promise_intro', 'promise_focus', 'grow_intro', 'next_intro',
        'journey_intro', 'session_intro', 'attendance_note', 'attendance_intro',
        'counter_intro', 'who_intro', 'fee_note', 'scholarship_note', 'gallery_intro',
        'reel_intro', 'reel_note', 'payment_intro', 'payment_note', 'assist_note',
    ] as $key) {
        if (! empty($d[$key]) && is_string($d[$key])) {
            $textBlocks[$key] = $d[$key];
        }
    }

    $focus = is_scalar($d['what_focus'] ?? $d['focus'] ?? null)
        ? (string) ($d['what_focus'] ?? $d['focus'])
        : '';
    $whatTitle = is_scalar($d['what_title'] ?? null) ? (string) $d['what_title'] : '';
    if ($whatTitle === '' && ! empty($d['highlight_title']) && is_string($d['highlight_title'])) {
        $whatTitle = $d['highlight_title'];
    }

    $listGroups = [];
    $listMap = [
        'why' => $d['why_title'] ?? 'Why BNS?',
        'learn' => $d['learn_title'] ?? 'What Will You Learn?',
        'benefits' => $d['benefits_title'] ?? 'Benefits',
        'highlights' => $d['highlights_title'] ?? 'Highlights',
        'bring' => $d['bring_title'] ?? 'Please Bring',
        'reminders' => $d['reminder_title'] ?? 'Important Reminders',
        'points' => $d['points_title'] ?? 'Key Points',
        'different' => $d['different_title'] ?? 'What Makes BNS Different?',
        'who' => $d['who_title'] ?? 'Who Can Join?',
        'during' => $d['during_title'] ?? 'During the Seminar',
        'bot_features' => $d['bot_list_title'] ?? 'BOT Features',
        'checklist' => $d['checklist_title'] ?? 'Checklist',
        'steps' => $d['steps_title'] ?? 'Steps',
        'items' => $d['items_title'] ?? ($d['list_title'] ?? 'Details'),
        'features' => $d['features_title'] ?? 'Features',
        'topics' => $d['learn_title'] ?? ($d['topics_title'] ?? 'What Will You Learn?'),
        'faqs' => $d['faq_title'] ?? 'FAQs',
        'links' => $d['links_title'] ?? 'Useful Links',
        'mission' => $d['mission_title'] ?? 'Our Mission',
        'experience' => $d['experience_title'] ?? 'You will experience',
        'travel' => $d['travel_title'] ?? 'Travel Reminder',
        'dress' => $d['dress_title'] ?? 'Dress Code',
        'make' => $d['make_title'] ?? 'Make the Most of Today',
        'carry' => $d['carry_title'] ?? 'Please Carry',
        'home' => $d['home_title'] ?? 'Before You Leave Home',
        'awaits' => $d['awaits_title'] ?? 'What Awaits You',
        'vision' => $d['vision_title'] ?? 'Vision',
        'final_tips' => $d['final_title'] ?? 'Final Reminder',
        'session_points' => $d['session_points_title'] ?? 'Session Highlights',
        'insights' => $d['insights_title'] ?? 'You will gain insights into:',
        'next' => $d['next_title'] ?? 'What Happens Next?',
        'promise' => $d['promise_title'] ?? 'My Promise to You',
        'grow' => $d['grow_title'] ?? "Let's Grow Together",
        'success_lines' => $d['success_title'] ?? 'Your Success is Our Mission',
        'process' => $d['process_title'] ?? 'Process',
        'social' => $d['social_title'] ?? 'Follow Us',
        'venue_lines' => $d['venue_title'] ?? 'Venue',
        'recommended' => $d['recommended_title'] ?? 'Recommended Dress Code',
        'reasons' => $d['reasons_title'] ?? 'Why Visit?',
        'payment_benefits' => $d['payment_benefits_title'] ?? 'Payment Benefits',
        'why_pay' => $d['why_pay_title'] ?? 'Why Pay Now?',
        'rules' => $d['rules_title'] ?? 'Seminar Instructions',
        'mobile' => $d['mobile_title'] ?? 'Mobile Phone',
        'attendance' => $d['attendance_title'] ?? 'Attendance',
        'seating' => $d['seating_title'] ?? 'Seating',
    ];
    foreach ($listMap as $key => $label) {
        if (! empty($d[$key]) && is_array($d[$key])) {
            // mission/vision may be strings already handled above
            if ($key === 'mission' && isset($textBlocks['mission'])) {
                continue;
            }
            if ($key === 'vision' && isset($textBlocks['vision'])) {
                continue;
            }
            if ($key === 'dress' && isset($textBlocks['dress'])) {
                continue;
            }
            if ($key === 'travel' && isset($textBlocks['travel'])) {
                continue;
            }
            $listGroups[] = [
                'title' => is_scalar($label) ? (string) $label : 'Details',
                'items' => $d[$key],
            ];
        }
    }

    // Prefer instructions key specially — avoid duplicating `during` icon rows
    $instructions = [];
    if (! empty($d['instructions']) && is_array($d['instructions'])) {
        $instructions[] = [
            'title' => $d['instructions_title'] ?? 'Important Instructions',
            'items' => $d['instructions'],
        ];
    } elseif (! empty($d['notes']) && is_array($d['notes'])) {
        $firstNote = reset($d['notes']);
        if (is_array($firstNote) && (isset($firstNote['text']) || isset($firstNote['icon']))) {
            $instructions[] = [
                'title' => $d['notes_title'] ?? 'Notes',
                'items' => $d['notes'],
            ];
        }
    }

    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $journeys = is_array($d['journeys'] ?? null) ? $d['journeys'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    if ($motto === [] && $mottoFromTagline !== []) {
        $motto = $mottoFromTagline;
    }

    $venue = [];
    if (! empty($d['venue']) && is_array($d['venue'])) {
        $venue = $d['venue'];
    } elseif (! empty($d['address']) && is_array($d['address'])) {
        $venue = ['address' => $d['address']];
    }
    $address = $venue['address'] ?? (
        (! empty($venue['title']) || ! empty($venue['lines']))
            ? ['title' => $venue['title'] ?? '', 'lines' => $venue['lines'] ?? [], 'maps_url' => $venue['maps_url'] ?? '']
            : []
    );

    $date = is_scalar($d['date'] ?? null) ? (string) $d['date'] : '';
    $time = is_scalar($d['time'] ?? $d['seminar_time'] ?? null) ? (string) ($d['time'] ?? $d['seminar_time']) : '';
    $reporting = is_scalar($d['reporting'] ?? $d['report_time'] ?? null) ? (string) ($d['reporting'] ?? $d['report_time']) : '';

    $register = is_array($d['register'] ?? null) ? $d['register'] : [];
    $ctaCandidates = [
        $d['view_url'] ?? null,
        $d['portal_url'] ?? null,
        $d['pay_url'] ?? null,
        $d['register_url'] ?? null,
        $d['about_url'] ?? null,
        $register['url'] ?? null,
        $d['website'] ?? null,
    ];
    $ctaUrl = null;
    foreach ($ctaCandidates as $candidate) {
        if (is_string($candidate) && $candidate !== '') {
            $ctaUrl = $candidate;
            break;
        }
    }
    $ctaLabelCandidates = [
        $d['view_label'] ?? null,
        $d['portal_label'] ?? null,
        $d['pay_label'] ?? null,
        $d['register_label'] ?? null,
        $register['label'] ?? null,
    ];
    $ctaLabel = 'Open Link';
    foreach ($ctaLabelCandidates as $candidate) {
        if (is_string($candidate) && $candidate !== '') {
            $ctaLabel = $candidate;
            break;
        }
    }

    $heroTone = match ((string) ($item['layout'] ?? '')) {
        'confirm', 'thanks', 'welcome', 'welcomereg', 'attendance' => 'green',
        'countdown', 'reminder', 'today', 'tomorrow', 'admitreminder', 'paynow' => 'red',
        default => 'navy',
    };
    $heroBg = match ($heroTone) {
        'green' => 'linear-gradient(135deg,#0a1d37 0%,#14532d 55%,#166534 100%)',
        'red' => 'linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%)',
        default => 'linear-gradient(135deg,#0a1d37 0%,#1e3a5f 50%,#0d2944 100%)',
    };
    $badgeBg = match ($heroTone) {
        'green' => '#16a34a',
        'red' => '#dc2626',
        default => 'linear-gradient(135deg,#fd8a2e,#fd6e01)',
    };
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:{{ $heroBg }};">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:{{ $badgeBg }};font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            @if(!empty($d['days']))
                <p style="margin:0 0 8px;font-size:48px;line-height:1;font-weight:900;color:#ffffff;">{{ $d['days'] }}</p>
            @endif
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($sub !== '')
                <p style="margin:0;font-size:14px;line-height:1.55;color:rgba(255,255,255,0.88);">{{ $sub }}</p>
            @endif
        </td>
    </tr>
</table>

@if($textBlocks !== [] || $focus !== '' || $whatTitle !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($whatTitle !== '')
                <h3 style="margin:0 0 10px;font-size:16px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $whatTitle }}</span>
                </h3>
            @endif
            @foreach([
                'greeting','congrats','thanks','appreciation','growth','hook','intro','designed','clarity',
                'request','opportunity','question','answer','what_intro','welcome','status','confirmed',
                'reserved','urgency','complete','highlight','belief','delighted','community','founding',
                'surprise','teaser','reveal','special','attendance_note','attendance_intro','counter_intro',
                'who_intro','fee_note','scholarship_note','gallery_intro','reel_intro','reel_note',
                'payment_intro','payment_note','assist_note','glimpse','journey','thanks_note',
            ] as $key)
                @if(!empty($textBlocks[$key]))
                    <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#334155;{{ in_array($key, ['greeting','congrats','status','confirmed'], true) ? 'font-weight:700;color:#0a1d37;' : '' }}">
                        @if($key === 'congrats')🎉 @endif
                        @if($key === 'status' || $key === 'confirmed')✓ @endif
                        {{ $textBlocks[$key] }}
                    </p>
                @endif
            @endforeach
            @if($focus !== '')
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:4px 0 0;border:1px solid rgba(253,110,1,0.22);border-radius:12px;background:rgba(253,110,1,0.08);">
                    <tr>
                        <td style="padding:12px 14px;font-size:14px;line-height:1.65;font-weight:650;color:#0a1d37;">{{ $focus }}</td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($date !== '' || $time !== '' || $reporting !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if($date !== '')
        <td width="{{ $time !== '' || $reporting !== '' ? '50%' : '100%' }}" valign="top" style="padding:0 6px 8px 0;">
            <table role="presentation" width="100%" style="border:1px solid #ffd4cc;border-radius:14px;background:#fff8f7;">
                <tr><td style="padding:14px;"><p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">📅 Date</p><p style="margin:0;font-size:14px;font-weight:800;line-height:1.4;color:#0a1d37;">{{ $date }}</p></td></tr>
            </table>
        </td>
        @endif
        @if($time !== '')
        <td width="50%" valign="top" style="padding:0 0 8px 6px;">
            <table role="presentation" width="100%" style="border:1px solid #ffd4cc;border-radius:14px;background:#fff8f7;">
                <tr><td style="padding:14px;"><p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">🕘 Time</p><p style="margin:0;font-size:14px;font-weight:800;line-height:1.4;color:#0a1d37;">{{ $time }}</p></td></tr>
            </table>
        </td>
        @endif
    </tr>
    @if($reporting !== '')
    <tr>
        <td colspan="2" style="padding:0;">
            <table role="presentation" width="100%" style="border:1px solid #bbf7d0;border-radius:14px;background:#f0fdf4;">
                <tr><td style="padding:14px;"><p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#166534;">⏰ Reporting</p><p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $reporting }}</p></td></tr>
            </table>
        </td>
    </tr>
    @endif
</table>
@endif

@if($sessions !== [])
@include('emails.layouts._ui-sessions', [
    'sessions' => $sessions,
    'sessionsTitle' => $d['sessions_title'] ?? ($d['session_title'] ?? 'Choose Your Preferred Session'),
])
@endif

@if($journeys !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">Learning Journeys</span>
            </h3>
            @include('emails.layouts._checklist', ['items' => collect($journeys)->map(fn ($j) => is_array($j) ? ($j['text'] ?? '') : $j)->all()])
        </td>
    </tr>
</table>
@endif

@php
    $simpleVenue = [];
    if (! empty($d['venue']) && is_array($d['venue']) && (isset($d['venue']['lines']) || isset($d['venue']['maps_url']))) {
        $simpleVenue = $d['venue'];
    }
    $useFullVenueCard = ! empty($address['title']) || ! empty($address['lines']) || ! empty($address['maps_url']) || ! empty($venue['title']);
@endphp
@if($useFullVenueCard)
@include('emails.partials.session-venue-card', [
    'venueCard' => [
        'eyebrow' => $d['venue_eyebrow'] ?? 'Event Location',
        'headline' => $d['venue_headline'] ?? ($d['headline'] ?? 'Venue Details'),
        'intro' => '',
        'date' => $date !== '' ? $date : ($sessions[0]['date'] ?? ''),
        'time' => $time !== '' ? $time : ($sessions[0]['time'] ?? ''),
        'address' => [
            'title' => $address['title'] ?? ($venue['title'] ?? ''),
            'lines' => $address['lines'] ?? ($venue['lines'] ?? []),
            'maps_url' => $address['maps_url'] ?? ($venue['maps_url'] ?? ''),
        ],
    ],
])
@elseif($simpleVenue !== [])
@include('emails.layouts._ui-venue-simple', ['venue' => $simpleVenue])
@endif

@foreach($listGroups as $group)
    @php
        $items = $group['items'] ?? [];
        $first = reset($items);
        $isLinkRows = is_array($first) && (isset($first['url']) || isset($first['href'])) && (isset($first['title']) || isset($first['label']) || isset($first['desc']));
        $isIconRows = is_array($first) && (isset($first['text']) || isset($first['icon']) || isset($first['title'])) && ! isset($first['q']) && ! isset($first['a']) && ! $isLinkRows;
        $isFaqRows = is_array($first) && (isset($first['q']) || isset($first['a']) || isset($first['question']) || isset($first['answer']));
    @endphp
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
        <tr>
            <td style="padding:18px;">
                <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $group['title'] }}</span>
                </h3>
                @if($isFaqRows)
                    @foreach($items as $index => $row)
                        @php
                            $q = (string) ($row['q'] ?? $row['question'] ?? $row['title'] ?? '');
                            $a = (string) ($row['a'] ?? $row['answer'] ?? $row['text'] ?? '');
                            $num = str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT);
                        @endphp
                        @if($q !== '' || $a !== '')
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                                <tr>
                                    <td width="36" valign="top" style="padding:10px 0 10px 12px;">
                                        <div style="width:24px;height:24px;border-radius:8px;background:#eef2ff;text-align:center;line-height:24px;font-size:11px;font-weight:800;color:#4f46e5;">{{ $num }}</div>
                                    </td>
                                    <td style="padding:10px 12px 10px 4px;">
                                        @if($q !== '')
                                            <p style="margin:0 0 4px;font-size:14px;line-height:1.4;font-weight:800;color:#0a1d37;">{{ $q }}</p>
                                        @endif
                                        @if($a !== '')
                                            <p style="margin:0;font-size:13px;line-height:1.55;color:#475569;">{{ $a }}</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @endif
                    @endforeach
                @elseif($isLinkRows)
                    @foreach($items as $row)
                        @php
                            $url = (string) ($row['url'] ?? $row['href'] ?? '');
                            $linkTitle = (string) ($row['title'] ?? $row['label'] ?? 'Open Link');
                            $desc = (string) ($row['desc'] ?? $row['meta'] ?? $row['text'] ?? '');
                            $icon = (string) ($row['icon'] ?? '🔗');
                            if (preg_match('/^(fas|fab|far)\s/i', $icon)) {
                                $icon = '🔗';
                            }
                            $ctaText = (string) ($row['cta'] ?? '');
                            if ($ctaText === '' && ! empty($row['title']) && ! empty($row['label']) && $row['label'] !== $row['title']) {
                                $ctaText = (string) $row['label'];
                            }
                            if ($ctaText === '') {
                                $ctaText = 'Open';
                            }
                        @endphp
                        @if($url !== '')
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                                <tr>
                                    <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:15px;">{{ $icon }}</td>
                                    <td style="padding:10px 12px 10px 4px;">
                                        <p style="margin:0 0 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $linkTitle }}</p>
                                        @if($desc !== '')
                                            <p style="margin:0 0 8px;font-size:13px;line-height:1.5;color:#64748b;">{{ $desc }}</p>
                                        @endif
                                        <a href="{{ $url }}" style="display:inline-block;padding:8px 12px;border-radius:999px;background:#fd6e01;color:#ffffff;font-size:12px;font-weight:700;text-decoration:none;">{{ $ctaText }}</a>
                                    </td>
                                </tr>
                            </table>
                        @endif
                    @endforeach
                @elseif($isIconRows)
                    @foreach($items as $row)
                        @php
                            $icon = (string) ($row['icon'] ?? '✓');
                            if (preg_match('/^(fas|fab|far)\s/i', $icon)) {
                                $icon = '✓';
                            }
                            $text = (string) ($row['text'] ?? $row['label'] ?? $row['title'] ?? $row['desc'] ?? '');
                        @endphp
                        @if($text !== '')
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                                <tr>
                                    <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:15px;">{{ $icon }}</td>
                                    <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                                </tr>
                            </table>
                        @endif
                    @endforeach
                @else
                    @include('emails.layouts._checklist', ['items' => $items])
                @endif
            </td>
        </tr>
    </table>
@endforeach

@foreach($instructions as $block)
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">📢 {{ $block['title'] }}</span>
            </h3>
            @foreach(($block['items'] ?? []) as $row)
                @php
                    $icon = is_array($row) ? (string) ($row['icon'] ?? '📌') : '📌';
                    $text = is_array($row) ? (string) ($row['text'] ?? '') : (string) $row;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:15px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:650;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endforeach

@if(!empty($textBlocks['mission']) || !empty($textBlocks['vision']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if(!empty($textBlocks['mission']))
        <td width="50%" valign="top" style="padding:0 6px 0 0;">
            <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;">
                <tr><td style="padding:16px;">
                    <p style="margin:0 0 6px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#fd6e01;">Mission</p>
                    <p style="margin:0;font-size:13px;line-height:1.65;color:#334155;">{{ $textBlocks['mission'] }}</p>
                </td></tr>
            </table>
        </td>
        @endif
        @if(!empty($textBlocks['vision']))
        <td width="50%" valign="top" style="padding:0 0 0 6px;">
            <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;">
                <tr><td style="padding:16px;">
                    <p style="margin:0 0 6px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#2563eb;">Vision</p>
                    <p style="margin:0;font-size:13px;line-height:1.65;color:#334155;">{{ $textBlocks['vision'] }}</p>
                </td></tr>
            </table>
        </td>
        @endif
    </tr>
</table>
@endif

@if(!empty($textBlocks['invite']) || !empty($textBlocks['invite_intro']) || !empty($d['invite_title']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:14px;background:#fff7ed;">
    <tr>
        <td style="padding:16px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">👨‍👩‍👧 {{ $d['invite_title'] ?? 'Invite Your Family & Friends' }}</h3>
            @if(!empty($textBlocks['invite_intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.7;color:#334155;">{{ $textBlocks['invite_intro'] }}</p>
            @endif
            @if(!empty($textBlocks['invite']))
                <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">{{ $textBlocks['invite'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['dress']) || !empty($d['dress_note']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
    <tr>
        <td style="padding:16px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">👔 {{ $d['dress_title'] ?? 'Dress Code' }}</h3>
            @if(!empty($d['dress']) && is_string($d['dress']))
                <p style="margin:0 0 6px;font-size:14px;font-weight:700;color:#0a1d37;">{{ $d['dress'] }}</p>
            @endif
            @if(!empty($d['dress_note']) && is_string($d['dress_note']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:#475569;">{{ $d['dress_note'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($partners !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @foreach($partners as $i => $partner)
            <td width="50%" valign="top" style="padding:{{ $i % 2 === 0 ? '0 6px 8px 0' : '0 0 8px 6px' }};">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                    <tr>
                        <td style="padding:12px;">
                            <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">{{ $partner['label'] ?? 'Partner' }}</p>
                            <p style="margin:0;font-size:13px;font-weight:700;line-height:1.45;color:#0a1d37;">{{ $partner['name'] ?? '' }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        @endforeach
    </tr>
</table>
@endif

@if(!empty($d['bot_number']) || !empty($d['bot_url']) || !empty($d['channel_url']) || !empty($d['website']) || !empty($d['web_url']) || $ctaUrl)
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
    <tr>
        <td style="padding:16px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🔗 Stay Connected</h3>
            @if(!empty($d['bot_number']) || !empty($d['bot_url']))
                <table role="presentation" width="100%" style="margin:0 0 12px;border:1px solid #bbf7d0;border-radius:12px;background:#f0fdf4;">
                    <tr>
                        <td style="padding:14px;">
                            <p style="margin:0 0 4px;font-size:13px;font-weight:800;color:#166534;">{{ $d['bot_title'] ?? 'WhatsApp BOT' }}</p>
                            @if(!empty($d['bot_number']))
                                <p style="margin:0 0 4px;font-size:18px;font-weight:800;color:#0a1d37;">{{ $d['bot_number'] }}</p>
                            @endif
                            @if(!empty($d['bot_hint']) || !empty($d['bot_intro']))
                                <p style="margin:0 0 10px;font-size:13px;color:#475569;">{{ $d['bot_hint'] ?? $d['bot_intro'] }}</p>
                            @endif
                            @if(!empty($d['bot_url']))
                                <a href="{{ $d['bot_url'] }}" style="display:inline-block;padding:10px 14px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Message BOT</a>
                            @endif
                        </td>
                    </tr>
                </table>
            @endif
            <p style="margin:0;">
                @if($ctaUrl)
                    <a href="{{ $ctaUrl }}" style="display:inline-block;margin:0 8px 8px 0;padding:10px 14px;border-radius:999px;background:#fd6e01;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">{{ $ctaLabel }}</a>
                @endif
                @if(!empty($d['channel_url']))
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:10px 14px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">{{ $d['channel_title'] ?? 'WhatsApp Channel' }}</a>
                @endif
                @if(!empty($d['website']) && $d['website'] !== $ctaUrl)
                    <a href="{{ $d['website'] }}" style="display:inline-block;margin:0 0 8px 0;padding:10px 14px;border-radius:999px;background:#1e3a5f;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">{{ $d['web_label'] ?? 'Visit Website' }}</a>
                @endif
                @if(!empty($d['web_url']) && ($d['web_url'] ?? '') !== $ctaUrl && ($d['web_url'] ?? '') !== ($d['website'] ?? null))
                    <a href="{{ $d['web_url'] }}" style="display:inline-block;margin:0 0 8px 0;padding:10px 14px;border-radius:999px;background:#1e3a5f;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">{{ $d['web_label'] ?? 'Visit Website' }}</a>
                @endif
                @if(!empty($d['pay_url']) && $d['pay_url'] !== $ctaUrl)
                    <a href="{{ $d['pay_url'] }}" style="display:inline-block;margin:0 0 8px 0;padding:10px 14px;border-radius:999px;background:#dc2626;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">{{ $d['pay_label'] ?? 'Pay Now' }}</a>
                @endif
            </p>
        </td>
    </tr>
</table>
@endif

@if(!empty($d['badge']) && is_string($d['badge']))
<p style="margin:14px 0 0;display:inline-block;padding:8px 14px;border-radius:999px;background:#fff5f3;color:#dc2626;font-size:13px;font-weight:700;">{{ $d['badge'] }}</p>
@endif

@if(!empty($textBlocks['calendar_note']) || !empty($textBlocks['help']) || !empty($textBlocks['closing_thanks']) || !empty($textBlocks['closing']) || !empty($textBlocks['punch']) || !empty($textBlocks['dress_note']) && false)
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;">
    <tr>
        <td style="padding:16px;">
            @foreach(['calendar_note','help','closing_thanks','closing','punch'] as $key)
                @if(!empty($textBlocks[$key]))
                    <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#334155;">{{ $textBlocks[$key] }}</p>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if(!empty($d['motto_title']))
                <p style="margin:0 0 10px;font-size:13px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#fd8a2e;">{{ $d['motto_title'] }}</p>
            @endif
            @foreach($motto as $line)
                <p style="margin:0 0 6px;font-size:14px;font-weight:700;color:#ffffff;">
                    {{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}
                </p>
            @endforeach
        </td>
    </tr>
</table>
@endif

@php
    $usedKeys = [
        'sessions', 'journeys', 'partners', 'motto', 'venue', 'address', 'instructions', 'notes', 'register',
        'eyebrow', 'headline', 'brand', 'tagline', 'hero_sub', 'headline_sub', 'days', 'date', 'time',
        'seminar_time', 'reporting', 'report_time', 'what_focus', 'focus', 'what_title', 'highlight_title', 'badge',
    ];
    foreach (array_keys($textBlocks) as $textKey) {
        $usedKeys[] = $textKey;
    }
    foreach ($listMap as $listKey => $_label) {
        if (! empty($d[$listKey]) && is_array($d[$listKey])) {
            $usedKeys[] = $listKey;
            $usedKeys[] = $listKey.'_title';
        }
    }
@endphp
@include('emails.layouts._payload-remainder', ['data' => $d, 'used' => $usedKeys])
