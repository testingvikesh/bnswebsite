@php
    $d = is_array($data ?? null) ? $data : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $vision = is_array($d['vision'] ?? null) ? $d['vision'] : [];
    $experience = is_array($d['experience'] ?? null) ? $d['experience'] : [];
    $gujarati = is_array($d['gujarati'] ?? null) ? $d['gujarati'] : [];
    $highlights = is_array($d['highlights'] ?? null) ? $d['highlights'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    $mobileDigits = preg_replace('/\D+/', '', (string) ($d['mobile'] ?? ''));
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#14532d 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'From the Founder' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🤝</p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🌟 {{ $d['headline'] ?? 'A Personal Invitation from the Founder' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['intro'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['mission']) || $vision !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if(!empty($d['mission']))
                <p style="margin:0 0 14px;font-size:14px;line-height:1.7;color:#334155;">{{ $d['mission'] }}</p>
            @endif
            @if(!empty($d['vision_title']))
                <p style="margin:0 0 10px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['vision_title'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $vision])
        </td>
    </tr>
</table>
@endif

@if($experience !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $d['experience_title'] ?? 'In this Seminar, you will experience:' }}</h3>
            @include('emails.layouts._checklist', ['items' => $experience])
        </td>
    </tr>
</table>
@endif

@if($gujarati !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['gujarati_title']))
                <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $d['gujarati_title'] }}</h3>
            @endif
            @foreach($gujarati as $i => $line)
                <p style="margin:0 0 8px;font-size:14px;line-height:1.6;font-weight:{{ in_array($i, [0, 1, 3, 5], true) ? '800' : '600' }};color:#0a1d37;">{{ $line }}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif

@if(!empty($d['journey']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #dbeafe;border-radius:16px;background:#eff6ff;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <p style="margin:0;font-size:15px;line-height:1.7;font-weight:700;color:#1e3a5f;">{{ $d['journey'] }}</p>
        </td>
    </tr>
</table>
@endif

@if($highlights !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📚 {{ $d['highlights_title'] ?? 'Seminar Highlights' }}</h3>
            @include('emails.layouts._checklist', ['items' => $highlights])
            @if(!empty($d['certificate']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:12px 0 0;border:1px solid #fde68a;border-radius:12px;background:#fffbeb;">
                    <tr>
                        <td width="36" valign="middle" style="padding:12px 0 12px 12px;font-size:16px;">🏆</td>
                        <td style="padding:12px 12px 12px 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['certificate'] }}</td>
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
        'sessionsTitle' => $d['sessions_title'] ?? 'Choose Your Session',
    ])
@elseif(!empty($d['date']) || !empty($d['time']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if(!empty($d['date']))
                <p style="margin:0 0 8px;font-size:14px;color:#0a1d37;"><strong>📅 Date:</strong> {{ $d['date'] }}</p>
            @endif
            @if(!empty($d['time']))
                <p style="margin:0;font-size:14px;color:#0a1d37;"><strong>🕘 Time:</strong> {{ $d['time'] }}</p>
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

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['closing']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['closing'] }}</p>
            @endif
            @if(!empty($d['closing_note']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['closing_note'] }}</p>
            @endif
            @if(!empty($d['session_choice']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;font-weight:700;color:#0a1d37;">{{ $d['session_choice'] }}</p>
            @endif
            @if(!empty($d['regards']))
                <p style="margin:0 0 10px;font-size:13px;font-style:italic;color:#64748b;">{{ $d['regards'] }}</p>
            @endif
            @if(!empty($d['name']))
                <p style="margin:0;font-size:16px;font-weight:800;color:#0a1d37;">{{ $d['name'] }}</p>
            @endif
            @if(!empty($d['role']))
                <p style="margin:4px 0 0;font-size:13px;color:#475569;">{{ $d['role'] }}</p>
            @endif
            @if(!empty($d['brand']))
                <p style="margin:2px 0 0;font-size:13px;font-weight:700;color:#0a1d37;">{{ $d['brand'] }}</p>
            @endif
            @if(!empty($d['mobile']) && $mobileDigits !== '')
                <p style="margin:14px 0 0;">
                    <a href="https://wa.me/{{ $mobileDigits }}?text=Hello" style="display:inline-block;padding:10px 16px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['mobile_label'] ?? 'Mobile & WhatsApp' }}: {{ $d['mobile'] }}
                    </a>
                </p>
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
