@php
    $d = is_array($data ?? null) ? $data : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $travel = is_array($d['travel'] ?? null) ? $d['travel'] : [];
    $reportTimes = is_array($d['report_times'] ?? null) ? $d['report_times'] : [];
    $finalTips = is_array($d['final_tips'] ?? null) ? $d['final_tips'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    $websiteHost = ! empty($d['website']) ? preg_replace('#^https?://#', '', (string) $d['website']) : '';
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#1e40af 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Navigation Ready' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">📍</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Venue & GPS Reminder' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['intro_note']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $d['intro_note'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($sessions !== [])
    @include('emails.layouts._ui-sessions', [
        'sessions' => $sessions,
        'sessionsTitle' => $d['sessions_title'] ?? 'Choose Your Session',
    ])
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

@if(!empty($venue['maps_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bfdbfe;border-radius:16px;background:#eff6ff;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🗺️ {{ $venue['maps_title'] ?? 'Google Maps Location' }}</h3>
            @if(!empty($venue['maps_hint']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.55;color:#475569;">{{ $venue['maps_hint'] }}</p>
            @endif
            <a href="{{ $venue['maps_url'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#2563eb;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                Start Google Maps Navigation
            </a>
        </td>
    </tr>
</table>
@endif

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

@if($travel !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🚗 {{ $d['travel_title'] ?? 'Travel Reminder' }}</h3>
            @include('emails.layouts._checklist', ['items' => $travel])
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">⏰ {{ $d['report_title'] ?? 'Reporting Reminder' }}</h3>
            @if(!empty($d['report_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['report_intro'] }}</p>
            @endif
            @if($reportTimes !== [])
                <p style="margin:0 0 10px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#c2410c;">{{ $d['report_label'] ?? 'Reporting Time' }}</p>
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        @foreach($reportTimes as $i => $rt)
                            @php
                                $tone = strtolower((string) ($rt['tone'] ?? ($i === 0 ? 'green' : 'blue')));
                                $isBlue = $tone === 'blue';
                                $border = $isBlue ? '#bfdbfe' : '#bbf7d0';
                                $bg = $isBlue ? '#eff6ff' : '#f0fdf4';
                                $color = $isBlue ? '#1d4ed8' : '#15803d';
                            @endphp
                            <td width="50%" valign="top" style="padding:{{ $i % 2 === 0 ? '0 6px 0 0' : '0 0 0 6px' }};">
                                <table role="presentation" width="100%" style="border:1px solid {{ $border }};border-radius:12px;background:{{ $bg }};">
                                    <tr>
                                        <td style="padding:12px;text-align:center;">
                                            <p style="margin:0 0 4px;font-size:11px;font-weight:800;color:{{ $color }};">{{ $rt['label'] ?? 'Session' }}</p>
                                            <p style="margin:0;font-size:16px;font-weight:800;color:#0a1d37;">{{ $rt['time'] ?? '' }}</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        @endforeach
                    </tr>
                </table>
            @elseif(!empty($d['report_time']))
                <p style="margin:0 0 10px;font-size:14px;font-weight:800;color:#dc2626;">Reporting Time: {{ $d['report_time'] }}</p>
            @endif
            @if(!empty($d['report_note']))
                <p style="margin:10px 0 0;font-size:14px;line-height:1.55;color:#475569;">{{ $d['report_note'] }}</p>
            @endif
            @if(!empty($d['seats']))
                <p style="margin:12px 0 0;padding:10px 12px;border-radius:10px;background:#ffffff;border:1px solid #fed7aa;font-size:13px;font-weight:800;color:#0a1d37;">💺 {{ $d['seats'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['station']) || !empty($d['transport']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🚆 {{ $d['options_title'] ?? 'Travel Options' }}</h3>
            @if(!empty($d['station']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#0a1d37;">{{ $d['station'] }}</p>
            @endif
            @if(!empty($d['transport']))
                <p style="margin:0;font-size:14px;line-height:1.55;color:#475569;">{{ $d['transport'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['bot_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:16px;background:#f0fdf4;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">📲 {{ $d['assist_title'] ?? 'Need Any Assistance?' }}</h3>
            <a href="{{ $d['bot_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                {{ $d['bot_label'] ?? 'WhatsApp BOT' }}@if(!empty($d['bot_hint'])) — {{ $d['bot_hint'] }}@endif
            </a>
        </td>
    </tr>
</table>
@endif

@if(!empty($d['channel_url']) || !empty($d['website']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🌐 {{ $d['connect_title'] ?? 'Stay Connected' }}</h3>
            @if(!empty($d['channel_url']))
                <p style="margin:0 0 10px;">
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#128c7e;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_title'] ?? 'WhatsApp Channel' }}
                    </a>
                </p>
            @endif
            @if(!empty($d['website']))
                <p style="margin:0;">
                    <a href="{{ $d['website'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        🌐 {{ $websiteHost }}
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#ffffff;">🌟 {{ $d['final_title'] ?? 'Final Reminder' }}</h3>
            @foreach($finalTips as $tip)
                <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:rgba(255,255,255,0.92);">✓ {{ $tip }}</p>
            @endforeach
            @if(!empty($d['closing']))
                <p style="margin:12px 0 8px;font-size:14px;font-style:italic;color:rgba(255,255,255,0.88);">{{ $d['closing'] }}</p>
            @endif
            @if(!empty($d['brand']))
                <p style="margin:0 0 10px;font-size:14px;font-weight:800;color:#ffffff;">{{ $d['brand'] }}</p>
            @endif
            @foreach($motto as $line)
                <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#ffffff;">
                    {{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}
                </p>
            @endforeach
        </td>
    </tr>
</table>
