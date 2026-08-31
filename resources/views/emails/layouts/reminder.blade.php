@php
    $d = is_array($data ?? null) ? $data : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $checklist = is_array($d['checklist'] ?? null) ? $d['checklist'] : [];
    $travel = is_array($d['travel'] ?? null) ? $d['travel'] : [];
    $dress = is_array($d['dress'] ?? null) ? $d['dress'] : [];
    $instructions = is_array($d['instructions'] ?? null) ? $d['instructions'] : [];
    $make = is_array($d['make'] ?? null) ? $d['make'] : [];
    $rsvp = is_array($d['rsvp'] ?? null) ? $d['rsvp'] : (isset($d['rsvp']) ? [$d['rsvp']] : []);
    $actions = is_array($d['actions'] ?? null) ? $d['actions'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Last Call' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🔔</p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🌟 {{ $d['headline'] ?? 'Final Reminder' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0 0 6px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['thanks']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $d['thanks'] }}</p>
            @endif
        </td>
    </tr>
</table>

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

@if($checklist !== [] || $dress !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if($checklist !== [])
            <td width="{{ $dress !== [] ? '50%' : '100%' }}" valign="top" style="padding:0 {{ $dress !== [] ? '6px' : '0' }} 0 0;">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
                    <tr>
                        <td style="padding:16px;">
                            <h3 style="margin:0 0 12px;font-size:14px;font-weight:800;color:#0a1d37;">🎒 {{ $d['checklist_title'] ?? 'Final Checklist' }}</h3>
                            @include('emails.layouts._checklist', ['items' => $checklist])
                        </td>
                    </tr>
                </table>
            </td>
        @endif
        @if($dress !== [])
            <td width="{{ $checklist !== [] ? '50%' : '100%' }}" valign="top" style="padding:0 0 0 {{ $checklist !== [] ? '6px' : '0' }};">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
                    <tr>
                        <td style="padding:16px;">
                            <h3 style="margin:0 0 12px;font-size:14px;font-weight:800;color:#0a1d37;">👔 {{ $d['dress_title'] ?? 'Dress Code' }}</h3>
                            @include('emails.layouts._checklist', ['items' => $dress])
                        </td>
                    </tr>
                </table>
            </td>
        @endif
    </tr>
</table>
@endif

@if($travel !== [] || !empty($d['seats']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🚗 {{ $d['travel_title'] ?? 'Travel Reminder' }}</h3>
            @include('emails.layouts._checklist', ['items' => $travel])
            @if(!empty($d['seats']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:10px 0 0;border:1px solid #fed7aa;border-radius:12px;background:#ffffff;">
                    <tr>
                        <td width="36" valign="middle" style="padding:12px 0 12px 12px;font-size:16px;">💺</td>
                        <td style="padding:12px 12px 12px 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['seats'] }}</td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($instructions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📢 {{ $d['instructions_title'] ?? 'Important Instructions' }}</h3>
            @foreach($instructions as $note)
                @php
                    $icon = is_array($note) ? (string) ($note['icon'] ?? '📌') : '📌';
                    $text = is_array($note) ? (string) ($note['text'] ?? '') : (string) $note;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:650;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($make !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🤝 {{ $d['make_title'] ?? 'Make the Most of Today' }}</h3>
            @foreach($make as $note)
                @php
                    $icon = is_array($note) ? (string) ($note['icon'] ?? '📌') : '📌';
                    $text = is_array($note) ? (string) ($note['text'] ?? '') : (string) $note;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:650;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if(!empty($d['bot_url']) || !empty($d['channel_url']) || !empty($d['website']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📲 {{ $d['help_title'] ?? 'Need Any Help?' }}</h3>
            @if(!empty($d['bot_url']))
                <a href="{{ $d['bot_url'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:12px 14px;border-radius:12px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['bot_label'] ?? 'WhatsApp BOT' }}@if(!empty($d['bot_hint'])) — {{ $d['bot_hint'] }}@endif
                </a>
            @endif
            @if(!empty($d['channel_url']))
                <a href="{{ $d['channel_url'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:12px 14px;border-radius:12px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['channel_title'] ?? 'WhatsApp Channel' }}
                </a>
            @endif
            @if(!empty($d['website']))
                <a href="{{ $d['website'] }}" style="display:inline-block;margin:0 0 8px 0;padding:12px 14px;border-radius:12px;background:#fd6e01;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ preg_replace('#^https?://#', '', (string) $d['website']) }}
                </a>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['assist']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #fecaca;border-radius:16px;background:#fef2f2;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🆘 {{ $d['assist_title'] ?? 'Need Assistance?' }}</h3>
            <p style="margin:0;font-size:14px;line-height:1.65;color:#334155;">{{ $d['assist'] }}</p>
        </td>
    </tr>
</table>
@endif

@if($rsvp !== [] || !empty($d['rsvp_intro']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:16px;background:#f0fdf4;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">✅ {{ $d['rsvp_title'] ?? 'Attendance Update' }}</h3>
            @if(!empty($d['rsvp_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['rsvp_intro'] }}</p>
            @endif
            @foreach($rsvp as $i => $reply)
                @php $icon = $i === 0 ? '🚗' : '📍'; @endphp
                @if($i > 0)
                    <span style="display:inline-block;margin:0 8px;font-size:12px;font-weight:800;color:#94a3b8;">OR</span>
                @endif
                <span style="display:inline-block;margin:0 0 8px;padding:12px 18px;border-radius:999px;background:#16a34a;font-size:14px;font-weight:800;color:#ffffff;">{{ $icon }} {{ $reply }}</span>
            @endforeach
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#f8fafc;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $d['final_title'] ?? 'Final Message' }}</h3>
            @if(!empty($d['final']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $d['final'] }}</p>
            @endif
            @if(!empty($d['final_note']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['final_note'] }}</p>
            @endif
            @if($actions !== [])
                <p style="margin:0 0 10px;">
                    @foreach($actions as $action)
                        <span style="display:inline-block;margin:0 4px 6px;padding:7px 12px;border-radius:999px;background:#0a1d37;font-size:12px;font-weight:700;color:#ffffff;">{{ $action }}</span>
                    @endforeach
                </p>
            @endif
            @if(!empty($d['safe']))
                <p style="margin:0 0 8px;font-size:14px;font-style:italic;color:#475569;">{{ $d['safe'] }}</p>
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
