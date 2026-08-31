@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Admission Confirmed');
    $headline = (string) ($d['headline'] ?? 'Welcome to the BNS Family');
    $greeting = (string) ($d['greeting'] ?? '');
    $congrats = (string) ($d['congrats'] ?? '');
    $confirmed = (string) ($d['confirmed'] ?? '');
    $delighted = (string) ($d['delighted'] ?? '');
    $journey = is_array($d['journey'] ?? null) ? $d['journey'] : [];
    $next = is_array($d['next'] ?? null) ? $d['next'] : [];
    $instructions = is_array($d['instructions'] ?? null) ? $d['instructions'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#14532d 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🎉</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($congrats !== '')
                <p style="margin:0 0 8px;font-size:16px;font-weight:800;color:#86efac;">{{ $congrats }}</p>
            @endif
            @if($confirmed !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.92);">{{ $confirmed }}</p>
            @endif
            @if($delighted !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $delighted }}</p>
            @endif
        </td>
    </tr>
</table>

@if($journey !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $d['journey_title'] ?? 'Your Journey Begins Today' }}</h3>
            @if(!empty($d['journey_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['journey_intro'] }}</p>
            @endif
            @foreach($journey as $row)
                @php
                    $icon = is_array($row) ? (string) ($row['icon'] ?? '🌟') : '🌟';
                    $text = is_array($row) ? (string) ($row['text'] ?? '') : (string) $row;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #dcfce7;border-radius:12px;background:#f0fdf4;">
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

@if($next !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">📋 {{ $d['next_title'] ?? 'What Happens Next?' }}</h3>
            @if(!empty($d['next_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['next_intro'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $next])
        </td>
    </tr>
</table>
@endif

@if(!empty($d['web_url']) || !empty($d['bot_url']) || !empty($d['bot_number']) || !empty($d['channel_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📲 {{ $d['connect_title'] ?? 'Stay Connected' }}</h3>
            @if(!empty($d['web_url']))
                <p style="margin:0 0 10px;">
                    <a href="{{ $d['web_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['web_label'] ?? 'Official Website' }}
                    </a>
                </p>
            @endif
            @if(!empty($d['bot_number']) || !empty($d['bot_url']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 10px;border:1px solid #bbf7d0;border-radius:12px;background:#f0fdf4;">
                    <tr>
                        <td style="padding:14px;">
                            <p style="margin:0 0 4px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">{{ $d['bot_label'] ?? 'WhatsApp BOT' }}</p>
                            @if(!empty($d['bot_number']))
                                <p style="margin:0 0 4px;font-size:18px;font-weight:800;color:#0a1d37;">{{ $d['bot_number'] }}</p>
                            @endif
                            @if(!empty($d['bot_hint']))
                                <p style="margin:0 0 10px;font-size:13px;color:#64748b;">{{ $d['bot_hint'] }}</p>
                            @endif
                            @if(!empty($d['bot_url']))
                                <a href="{{ $d['bot_url'] }}" style="display:inline-block;padding:10px 14px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Message BOT</a>
                            @endif
                        </td>
                    </tr>
                </table>
            @endif
            @if(!empty($d['channel_url']))
                <p style="margin:0;">
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#128c7e;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_label'] ?? 'Join WhatsApp Channel' }}
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($instructions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📌 {{ $d['instructions_title'] ?? 'Important Instructions' }}</h3>
            @include('emails.layouts._checklist', ['items' => $instructions])
        </td>
    </tr>
</table>
@endif

@if(!empty($d['commit']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #dcfce7;border-radius:16px;background:#f0fdf4;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🌱 {{ $d['commit_title'] ?? 'Our Commitment' }}</h3>
            <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">{{ $d['commit'] }}</p>
        </td>
    </tr>
</table>
@endif

@if(!empty($d['again_title']) || !empty($d['family']) || !empty($d['excited']) || !empty($d['together']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['again_title']))
                <p style="margin:0 0 8px;font-size:13px;font-style:italic;font-weight:700;color:#fd8a2e;">{{ $d['again_title'] }}</p>
            @endif
            @if(!empty($d['family']))
                <p style="margin:0 0 8px;font-size:16px;font-weight:800;color:#ffffff;">{{ $d['family'] }}</p>
            @endif
            @if(!empty($d['excited']))
                <p style="margin:0 0 6px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['excited'] }}</p>
            @endif
            @if(!empty($d['together']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['together'] }}</p>
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
