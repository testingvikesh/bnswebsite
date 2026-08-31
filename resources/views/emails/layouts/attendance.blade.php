@php
    $d = is_array($data ?? null) ? $data : [];
    $channelBenefits = is_array($d['channel_benefits'] ?? null) ? $d['channel_benefits'] : [];
    $botFeatures = is_array($d['bot_features'] ?? null) ? $d['bot_features'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#14532d 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Seminar Welcome' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🌟</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Welcome to Business Navachar School (BNS)' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['thanks']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $d['thanks'] }}</p>
            @endif
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:16px;background:#f0fdf4;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">1️⃣ {{ $d['attendance_title'] ?? 'Attendance Registration' }}</h3>
            @if(!empty($d['attendance_intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['attendance_intro'] }}</p>
            @endif
            @if(!empty($d['attendance_note']))
                <p style="margin:0 0 12px;font-size:13px;line-height:1.55;color:#64748b;">{{ $d['attendance_note'] }}</p>
            @endif
            @if(!empty($d['attendance_url']))
                <a href="{{ $d['attendance_url'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['attendance_label'] ?? 'Mark Attendance' }}
                </a>
            @endif
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">2️⃣ {{ $d['channel_title'] ?? 'Join Our Official WhatsApp Channel' }}</h3>
            @if(!empty($d['channel_intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['channel_intro'] }}</p>
            @endif
            @if(!empty($d['channel_list_title']))
                <p style="margin:0 0 10px;font-size:13px;font-weight:700;color:#0a1d37;">{{ $d['channel_list_title'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $channelBenefits])
            @if(!empty($d['channel_url']))
                <p style="margin:12px 0 0;">
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#128c7e;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_label'] ?? 'Join WhatsApp Channel' }}
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">3️⃣ {{ $d['bot_title'] ?? 'Need More Information?' }}</h3>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #bbf7d0;border-radius:14px;background:#f0fdf4;">
                <tr>
                    <td style="padding:16px;">
                        <p style="margin:0 0 4px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">{{ $d['bot_label'] ?? 'BNS WhatsApp BOT' }}</p>
                        @if(!empty($d['bot_number']))
                            <p style="margin:0 0 6px;font-size:20px;font-weight:800;color:#0a1d37;">{{ $d['bot_number'] }}</p>
                        @endif
                        @if(!empty($d['bot_intro']))
                            <p style="margin:0 0 10px;font-size:13px;line-height:1.5;color:#64748b;">{{ $d['bot_intro'] }}</p>
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
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">4️⃣ {{ $d['admit_title'] ?? 'Book Your Admission' }}</h3>
            @if(!empty($d['admit_open']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.55;color:#334155;">{{ $d['admit_open'] }}</p>
            @endif
            @if(!empty($d['admit_seats']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#dc2626;">{{ $d['admit_seats'] }}</p>
            @endif
            @if(!empty($d['admit_cta']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.55;color:#475569;">{{ $d['admit_cta'] }}</p>
            @endif
            @if(!empty($d['admit_url']))
                <a href="{{ $d['admit_url'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#dc2626;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['admit_label'] ?? 'Book Admission' }}
                </a>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['inspire']) || !empty($d['closing']) || !empty($d['brand']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#14532d);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['inspire']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['inspire'] }}</p>
            @endif
            @if(!empty($d['closing']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['closing'] }}</p>
            @endif
            @if(!empty($d['brand']))
                <p style="margin:0 0 12px;font-size:13px;font-style:italic;color:rgba(255,255,255,0.85);">{{ $d['brand'] }}</p>
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
