@php
    $d = is_array($data ?? null) ? $data : [];
    $insights = is_array($d['insights'] ?? null) ? $d['insights'] : [];
    $nextSteps = is_array($d['next_steps'] ?? null) ? $d['next_steps'] : [];
    $gratitude = is_array($d['gratitude'] ?? null) ? $d['gratitude'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#9f1239 48%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fb7185,#e11d48);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Closing Note' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🙏</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Thank You' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['lead']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#fecdd3;">{{ $d['lead'] }}</p>
            @endif
            @if(!empty($d['appreciate']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['appreciate'] }}</p>
            @endif
            @if(!empty($d['presence']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $d['presence'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($insights !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $d['insights_title'] ?? 'We Hope You Gained Valuable Insights' }}</h3>
            @include('emails.layouts._checklist', ['items' => $insights])
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🎓 {{ $d['next_title'] ?? 'Ready for the Next Step?' }}</h3>
            @if(!empty($d['next_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['next_intro'] }}</p>
            @endif
            @foreach($nextSteps as $step)
                @php
                    $icon = is_array($step) ? (string) ($step['icon'] ?? '📌') : '📌';
                    $text = is_array($step) ? (string) ($step['text'] ?? '') : (string) $step;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #fed7aa;border-radius:12px;background:#ffffff;">
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

@if(!empty($d['web_url']) || !empty($d['bot_url']) || !empty($d['channel_url']))
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
            @if(!empty($d['bot_url']) || !empty($d['bot_number']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 10px;border:1px solid #bbf7d0;border-radius:12px;background:#f0fdf4;">
                    <tr>
                        <td style="padding:14px;">
                            <p style="margin:0 0 4px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">{{ $d['bot_label'] ?? 'BNS WhatsApp BOT' }}</p>
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
                        {{ $d['channel_label'] ?? 'WhatsApp Channel' }}
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #fecdd3;border-radius:16px;background:#fff1f2;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">❤️ {{ $d['gratitude_title'] ?? 'Our Gratitude' }}</h3>
            @if(!empty($d['gratitude_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.55;color:#475569;">{{ $d['gratitude_intro'] }}</p>
            @endif
            @foreach($gratitude as $item)
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #fecdd3;border-radius:12px;background:#ffffff;">
                    <tr>
                        <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">🤝</td>
                        <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $item }}</td>
                    </tr>
                </table>
            @endforeach
            @if(!empty($d['mission']))
                <p style="margin:10px 0 0;font-size:14px;line-height:1.65;color:#334155;">{{ $d['mission'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['again']) || !empty($d['family']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#9f1239);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['again']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['again'] }}</p>
            @endif
            @if(!empty($d['family']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['family'] }}</p>
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
