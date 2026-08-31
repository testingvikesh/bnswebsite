@php
    $d = is_array($data ?? null) ? $data : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $waiting = is_array($d['waiting'] ?? null) ? $d['waiting'] : [];
    $teaserLines = is_array($d['teaser_lines'] ?? null) ? $d['teaser_lines'] : [];
    $reminders = is_array($d['reminders'] ?? null) ? $d['reminders'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#7c2d12 48%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Exclusive Reveal' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🎁</p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🎉 {{ $d['headline'] ?? 'Big Surprise Awaits You!' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['intro'] }}</p>
            @endif
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:16px;font-weight:800;color:#0a1d37;">🎁 {{ $d['teaser_title'] ?? 'A Special Surprise Has Been Planned!' }}</h3>
            @if(!empty($d['teaser']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['teaser'] }}</p>
            @endif
            @foreach($teaserLines as $line)
                <p style="margin:0 0 6px;font-size:14px;line-height:1.55;font-style:italic;color:#475569;">{{ $line }}</p>
            @endforeach
            @if(!empty($d['teaser_punch']))
                <p style="margin:12px 0 0;display:inline-block;padding:10px 16px;border-radius:999px;background:#dc2626;font-size:14px;font-weight:800;color:#ffffff;">{{ $d['teaser_punch'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($waiting !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $d['waiting_title'] ?? 'What Could Be Waiting for You?' }}</h3>
            @foreach($waiting as $item)
                @php
                    $icon = is_array($item) ? (string) ($item['icon'] ?? '✨') : '✨';
                    $text = is_array($item) ? (string) ($item['text'] ?? '') : (string) $item;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
            @if(!empty($d['waiting_note']))
                <p style="margin:10px 0 0;font-size:13px;line-height:1.6;font-style:italic;color:#64748b;">{{ $d['waiting_note'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['exclusive']) || !empty($d['exclusive_alert']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #fecaca;border-radius:16px;background:#fef2f2;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🎯 {{ $d['exclusive_title'] ?? 'Exclusively for Registered Participants' }}</h3>
            @if(!empty($d['exclusive']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['exclusive'] }}</p>
            @endif
            @if(!empty($d['exclusive_alert']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #fca5a5;border-radius:12px;background:#ffffff;">
                    <tr>
                        <td width="36" valign="middle" style="padding:12px 0 12px 12px;font-size:16px;">⚠️</td>
                        <td style="padding:12px 12px 12px 4px;font-size:14px;font-weight:800;color:#b91c1c;">{{ $d['exclusive_alert'] }}</td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($sessions !== [])
    @include('emails.layouts._ui-sessions', [
        'sessions' => $sessions,
        'sessionsTitle' => $d['sessions_title'] ?? 'Seminar Sessions',
    ])
@elseif(!empty($d['date']) || !empty($d['time']) || !empty($d['report_time']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if(!empty($d['date']))
                <p style="margin:0 0 8px;font-size:14px;color:#0a1d37;"><strong>📅 Date:</strong> {{ $d['date'] }}</p>
            @endif
            @if(!empty($d['time']))
                <p style="margin:0 0 8px;font-size:14px;color:#0a1d37;"><strong>🕘 Seminar Time:</strong> {{ $d['time'] }}</p>
            @endif
            @if(!empty($d['report_time']))
                <p style="margin:0;font-size:14px;font-weight:700;color:#dc2626;"><strong>⏰ Reporting Time:</strong> {{ $d['report_time'] }}</p>
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

@if($reminders !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📢 {{ $d['reminder_title'] ?? 'Important Reminder' }}</h3>
            @include('emails.layouts._checklist', ['items' => $reminders])
        </td>
    </tr>
</table>
@endif

@if(!empty($d['rsvp']) || !empty($d['rsvp_intro']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:16px;background:#f0fdf4;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🙋 {{ $d['rsvp_title'] ?? 'Attendance Confirmation' }}</h3>
            @if(!empty($d['rsvp_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['rsvp_intro'] }}</p>
            @endif
            @if(!empty($d['rsvp']))
                <p style="margin:0 0 10px;display:inline-block;padding:12px 18px;border-radius:999px;background:#16a34a;font-size:14px;font-weight:800;color:#ffffff;">✅ {{ is_array($d['rsvp']) ? implode(' / ', $d['rsvp']) : $d['rsvp'] }}</p>
            @endif
            @if(!empty($d['rsvp_note']))
                <p style="margin:10px 0 0;font-size:13px;font-style:italic;color:#64748b;">{{ $d['rsvp_note'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#f8fafc;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['closing']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;font-weight:700;color:#0a1d37;">{{ $d['closing'] }}</p>
            @endif
            @if(!empty($d['brand']))
                <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['brand'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @foreach($motto as $line)
                <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#ffffff;">
                    {{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}
                </p>
            @endforeach
        </td>
    </tr>
</table>
@endif
