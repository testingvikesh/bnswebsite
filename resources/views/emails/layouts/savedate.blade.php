@php
    $d = is_array($data ?? null) ? $data : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Calendar Invite' }}
            </p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🌟 {{ $d['headline'] ?? 'Save the Date' }}</h2>
            @if(!empty($d['mark']))
                <p style="margin:0;font-size:15px;font-weight:700;color:rgba(255,255,255,0.92);">{{ $d['mark'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['intro']) || !empty($d['invite']) || !empty($d['reserve']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if(!empty($d['intro']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#334155;">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['invite']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.7;font-weight:700;color:#0a1d37;">{{ $d['invite'] }}</p>
            @endif
            @if(!empty($d['reserve']))
                <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">{{ $d['reserve'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($sessions !== [])
    @include('emails.layouts._ui-sessions', [
        'sessions' => $sessions,
        'sessionsTitle' => $d['sessions_title'] ?? 'Choose Your Session',
    ])
@elseif(!empty($d['date']) || !empty($d['time']) || !empty($d['reporting']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if(!empty($d['date']))
                <p style="margin:0 0 8px;font-size:14px;color:#0a1d37;"><strong>📅 Date:</strong> {{ $d['date'] }}</p>
            @endif
            @if(!empty($d['time']))
                <p style="margin:0 0 8px;font-size:14px;color:#0a1d37;"><strong>🕘 Session Time:</strong> {{ $d['time'] }}</p>
            @endif
            @if(!empty($d['reporting']))
                <p style="margin:0;font-size:14px;font-weight:700;color:#16a34a;"><strong>⏰ Reporting Time:</strong> {{ $d['reporting'] }}</p>
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

@if(!empty($d['calendar_note']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:14px;background:#fff7ed;">
    <tr>
        <td width="40" valign="top" style="padding:14px 0 14px 14px;font-size:18px;">📌</td>
        <td style="padding:14px 14px 14px 4px;">
            <p style="margin:0;font-size:14px;line-height:1.65;color:#334155;">{{ $d['calendar_note'] }}</p>
        </td>
    </tr>
</table>
@endif

@if(!empty($venue['maps_url']) || !empty($d['events_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if(!empty($venue['maps_url']))
            <td width="{{ !empty($d['events_url']) ? '50%' : '100%' }}" style="padding:0 {{ !empty($d['events_url']) ? '6px' : '0' }} 0 0;">
                <a href="{{ $venue['maps_url'] }}" style="display:block;padding:14px 16px;border-radius:14px;background:#2563eb;color:#ffffff;text-align:center;font-size:13px;font-weight:800;text-decoration:none;">
                    📍 Open GPS
                </a>
            </td>
        @endif
        @if(!empty($d['events_url']))
            <td width="{{ !empty($venue['maps_url']) ? '50%' : '100%' }}" style="padding:0 0 0 {{ !empty($venue['maps_url']) ? '6px' : '0' }};">
                <a href="{{ $d['events_url'] }}" style="display:block;padding:14px 16px;border-radius:14px;background:#0a1d37;color:#ffffff;text-align:center;font-size:13px;font-weight:800;text-decoration:none;">
                    📅 View Events
                </a>
            </td>
        @endif
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#f8fafc;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['welcome']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['welcome'] }}</p>
            @endif
            @if(!empty($d['see_you']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $d['see_you'] }}</p>
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
