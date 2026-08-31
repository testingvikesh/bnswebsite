@php
    $d = is_array($data ?? null) ? $data : [];
    $rawSessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $sessions = collect($rawSessions)->map(function ($s) {
        $s = is_array($s) ? $s : [];
        return array_merge($s, [
            'time' => $s['time'] ?? $s['seminar_time'] ?? '',
            'reporting' => $s['reporting'] ?? $s['report_time'] ?? '',
        ]);
    })->all();
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $why = is_array($d['why'] ?? null) ? $d['why'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Be On Time' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">⏰</p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Reporting Time' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['intro'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($sessions !== [])
    @include('emails.layouts._ui-sessions', [
        'sessions' => $sessions,
        'sessionsTitle' => $d['details_title'] ?? 'Reporting Details',
    ])
@elseif(!empty($d['date']) || !empty($d['report_time']) || !empty($d['seminar_time']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📅 {{ $d['details_title'] ?? 'Reporting Details' }}</h3>
            @if(!empty($d['date']))
                <p style="margin:0 0 8px;font-size:14px;color:#0a1d37;"><strong>Date:</strong> {{ $d['date'] }}</p>
            @endif
            @if(!empty($d['report_time']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#dc2626;"><strong>Reporting Time:</strong> {{ $d['report_time'] }}</p>
            @endif
            @if(!empty($d['seminar_time']))
                <p style="margin:0;font-size:14px;color:#0a1d37;"><strong>Seminar Time:</strong> {{ $d['seminar_time'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($why !== [] || !empty($d['why_intro']) || !empty($d['why_title']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">📌 {{ $d['why_title'] ?? 'Why Report Early?' }}</h3>
            @if(!empty($d['why_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['why_intro'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $why])
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

@if(!empty($d['travel']) || !empty($d['seats']) || !empty($d['seats_note']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📍 {{ $d['note_title'] ?? 'Important Note' }}</h3>
            @if(!empty($d['travel']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:#334155;">🚗 {{ $d['travel'] }}</p>
            @endif
            @if(!empty($d['seats']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #fed7aa;border-radius:12px;background:#ffffff;">
                    <tr>
                        <td width="36" valign="middle" style="padding:12px 0 12px 12px;font-size:16px;">💺</td>
                        <td style="padding:12px 12px 12px 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['seats'] }}</td>
                    </tr>
                </table>
            @endif
            @if(!empty($d['seats_note']))
                <p style="margin:0;font-size:13px;line-height:1.6;color:#64748b;">{{ $d['seats_note'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#f8fafc;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['closing']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['closing'] }}</p>
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
