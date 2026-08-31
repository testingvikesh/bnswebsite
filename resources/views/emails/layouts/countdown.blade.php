@php
    $d = is_array($data ?? null) ? $data : [];
    $learn = is_array($d['learn'] ?? null) ? $d['learn'] : [];
    $highlights = is_array($d['highlights'] ?? null) ? $d['highlights'] : [];
    $bring = is_array($d['bring'] ?? null) ? $d['bring'] : [];
    $reminders = is_array($d['reminders'] ?? null) ? $d['reminders'] : [];
    $during = is_array($d['during'] ?? null) ? $d['during'] : [];
    $botFeatures = is_array($d['bot_features'] ?? null) ? $d['bot_features'] : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    $websiteHost = ! empty($d['website']) ? preg_replace('#^https?://#', '', (string) $d['website']) : '';
    $richSessions = false;
    foreach ($sessions as $session) {
        if (! empty($session['reporting']) || ! empty($session['tone'])) {
            $richSessions = true;
            break;
        }
    }
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 10px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Countdown Alert' }}
            </p>
            <p style="margin:0 0 8px;font-size:48px;line-height:1;font-weight:900;color:#ffffff;">{{ $d['days'] ?? '03' }}</p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">⏳ {{ $d['headline'] ?? 'Only 3 Days Left!' }}</h2>
            @if(!empty($d['tagline']))
                <p style="margin:0;font-size:14px;color:rgba(255,255,255,0.88);">{{ $d['tagline'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['thanks']) || !empty($d['reserved']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffd4cc;border-radius:14px;background:#fff8f7;">
    <tr>
        <td style="padding:16px;">
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['reserved']))
                <p style="margin:0;font-size:14px;font-weight:700;color:#0a1d37;">✓ {{ $d['reserved'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($sessions !== [] && $richSessions)
    @include('emails.layouts._ui-sessions', [
        'sessions' => $sessions,
        'sessionsTitle' => $d['sessions_title'] ?? 'Choose Your Session',
    ])
@elseif($sessions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
    <tr>
        <td style="padding:16px;">
            @if(!empty($d['sessions_title']))
                <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $d['sessions_title'] }}</h3>
            @endif
            @foreach($sessions as $session)
                <p style="margin:0 0 8px;font-size:14px;line-height:1.5;color:#0a1d37;">
                    <strong>{{ $session['label'] ?? '' }}</strong><br>
                    {{ $session['date'] ?? '' }}@if(!empty($session['time'])) · {{ $session['time'] }}@endif
                </p>
            @endforeach
        </td>
    </tr>
</table>
@elseif(!empty($d['date']) || !empty($d['time']) || !empty($d['report_time']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if(!empty($d['date']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#0a1d37;">📅 Date: {{ $d['date'] }}</p>
            @endif
            @if(!empty($d['time']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#0a1d37;">🕘 Seminar Time: {{ $d['time'] }}</p>
            @endif
            @if(!empty($d['report_time']))
                <p style="margin:0;font-size:14px;font-weight:800;color:#dc2626;">⏰ Reporting Time: {{ $d['report_time'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@include('emails.layouts._ui-venue-simple', ['venue' => $venue])

@if($partners !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @foreach($partners as $i => $partner)
            <td width="50%" valign="top" style="padding:{{ $i % 2 === 0 ? '0 6px 0 0' : '0 0 0 6px' }};">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                    <tr>
                        <td style="padding:14px;">
                            <p style="margin:0 0 6px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">{{ $partner['label'] ?? 'Partner' }}</p>
                            <p style="margin:0;font-size:13px;font-weight:800;line-height:1.45;color:#0a1d37;">{{ $partner['name'] ?? '' }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        @endforeach
    </tr>
</table>
@endif

@if($learn !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">🎯 {{ $d['learn_title'] ?? 'What Will You Learn?' }}</h3>
            @include('emails.layouts._checklist', ['items' => $learn])
        </td>
    </tr>
</table>
@endif

@if($highlights !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ede9fe;border-radius:16px;background:#faf5ff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">🌟 {{ $d['highlights_title'] ?? 'Previous Seminar Highlights' }}</h3>
            @foreach($highlights as $item)
                @php
                    $icon = is_array($item) ? (string) ($item['icon'] ?? '✨') : '✨';
                    $text = is_array($item) ? (string) ($item['text'] ?? '') : (string) $item;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #ede9fe;border-radius:12px;background:#ffffff;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
            @if(!empty($d['highlights_note']))
                <p style="margin:10px 0 0;font-size:13px;line-height:1.55;color:#64748b;">{{ $d['highlights_note'] }}</p>
            @endif
            @if(!empty($d['highlights_cta']))
                <p style="margin:8px 0 0;font-size:14px;font-weight:800;color:#7c3aed;">{{ $d['highlights_cta'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['website']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
    <tr>
        <td style="padding:16px;text-align:center;">
            <p style="margin:0 0 6px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">Visit Our Website</p>
            @if(!empty($d['website_intro']))
                <p style="margin:0 0 10px;font-size:13px;line-height:1.55;color:#475569;">{{ $d['website_intro'] }}</p>
            @endif
            <a href="{{ $d['website'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                🌐 {{ $websiteHost }}
            </a>
        </td>
    </tr>
</table>
@endif

@if(!empty($d['dress']) || !empty($d['dress_note']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">👔 {{ $d['dress_title'] ?? 'Dress Code' }}</h3>
            @if(!empty($d['dress']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#0a1d37;">{{ $d['dress'] }}</p>
            @endif
            @if(!empty($d['dress_note']))
                <p style="margin:0;font-size:14px;line-height:1.55;color:#475569;">{{ $d['dress_note'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($bring !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">🎒 {{ $d['bring_title'] ?? 'Please Bring' }}</h3>
            @include('emails.layouts._checklist', ['items' => $bring])
        </td>
    </tr>
</table>
@endif

@if($reminders !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">⏰ {{ $d['reminder_title'] ?? 'Important Reminder' }}</h3>
            @include('emails.layouts._checklist', ['items' => $reminders])
        </td>
    </tr>
</table>
@endif

@if($during !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e0e7ff;border-radius:16px;background:#eef2ff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📱 {{ $d['during_title'] ?? 'During the Seminar' }}</h3>
            @foreach($during as $item)
                @php
                    $icon = is_array($item) ? (string) ($item['icon'] ?? '📌') : '📌';
                    $text = is_array($item) ? (string) ($item['text'] ?? '') : (string) $item;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #c7d2fe;border-radius:12px;background:#ffffff;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if(!empty($d['channel_url']) || !empty($d['bot_number']) || !empty($d['bot_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if(!empty($d['channel_url']))
                <p style="margin:0 0 12px;">
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#128c7e;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_title'] ?? 'Join WhatsApp Channel' }}
                    </a>
                </p>
            @endif
            @if(!empty($d['bot_number']) || !empty($d['bot_url']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #bbf7d0;border-radius:14px;background:#f0fdf4;">
                    <tr>
                        <td style="padding:16px;">
                            <p style="margin:0 0 4px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">{{ $d['bot_title'] ?? 'Need Any Help?' }}</p>
                            @if(!empty($d['bot_intro']))
                                <p style="margin:0 0 6px;font-size:13px;color:#64748b;">{{ $d['bot_intro'] }}</p>
                            @endif
                            @if(!empty($d['bot_number']))
                                <p style="margin:0 0 8px;font-size:20px;font-weight:800;color:#0a1d37;">{{ $d['bot_number'] }}</p>
                            @endif
                            @if(!empty($d['bot_list_title']))
                                <p style="margin:0 0 8px;font-size:13px;font-weight:700;color:#0a1d37;">{{ $d['bot_list_title'] }}</p>
                            @endif
                            @include('emails.layouts._checklist', ['items' => $botFeatures])
                            @if(!empty($d['bot_url']))
                                <p style="margin:12px 0 0;">
                                    <a href="{{ $d['bot_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Message BOT</a>
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['invite']) || !empty($d['invite_intro']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🤝 {{ $d['invite_title'] ?? 'Invite Your Family & Friends' }}</h3>
            @if(!empty($d['invite_intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.55;font-weight:700;color:#9a3412;">{{ $d['invite_intro'] }}</p>
            @endif
            @if(!empty($d['invite']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:#334155;">{{ $d['invite'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['closing']) || !empty($d['welcome']) || !empty($d['brand']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#7f1d1d);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['closing']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['closing'] }}</p>
            @endif
            @if(!empty($d['welcome']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['welcome'] }}</p>
            @endif
            @if(!empty($d['brand']))
                <p style="margin:0 0 12px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['brand'] }}</p>
            @endif
            @foreach($motto as $line)
                <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#ffffff;">
                    {{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}
                </p>
            @endforeach
        </td>
    </tr>
</table>
@endif
