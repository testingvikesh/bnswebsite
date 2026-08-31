@php
    $d = is_array($data ?? null) ? $data : [];
    $whoItems = is_array($d['who_items'] ?? null) ? $d['who_items'] : [];
    $helpItems = is_array($d['help_items'] ?? null) ? $d['help_items'] : [];
    $payItems = is_array($d['pay_items'] ?? null) ? $d['pay_items'] : [];
    $assistLines = is_array($d['assist_lines'] ?? null) ? $d['assist_lines'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'During Seminar' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🎓</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Admission Counter Open' }}</h2>
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['inspire']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['inspire'] }}</p>
            @endif
            @if(!empty($d['open']))
                <p style="margin:0;display:inline-block;padding:8px 14px;border-radius:999px;background:rgba(255,255,255,0.15);font-size:13px;font-weight:800;color:#fecaca;">{{ $d['open'] }}</p>
            @endif
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🎯 {{ $d['who_title'] ?? 'Who Should Visit?' }}</h3>
            @if(!empty($d['who_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.55;color:#475569;">{{ $d['who_intro'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $whoItems])
            @if(!empty($d['who_closing']))
                <p style="margin:12px 0 0;font-size:14px;font-weight:700;color:#0a1d37;">{{ $d['who_closing'] }}</p>
            @endif
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📚 {{ $d['help_title'] ?? 'Admission Team Will Help You With' }}</h3>
            @include('emails.layouts._checklist', ['items' => $helpItems])
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bfdbfe;border-radius:16px;background:#eff6ff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">💳 {{ $d['pay_title'] ?? 'Payment Options' }}</h3>
            @include('emails.layouts._checklist', ['items' => $payItems])
        </td>
    </tr>
</table>

@if($assistLines !== [] || !empty($d['assist_title']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e0e7ff;border-radius:16px;background:#eef2ff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🙋 {{ $d['assist_title'] ?? 'Need Any Assistance?' }}</h3>
            @foreach($assistLines as $line)
                <p style="margin:0 0 6px;font-size:14px;line-height:1.55;color:#334155;">{{ $line }}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #fecaca;border-radius:16px;background:#fef2f2;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">⏳ {{ $d['urgent_title'] ?? 'Important Information' }}</h3>
            @if(!empty($d['urgent_basis']))
                <p style="margin:0 0 8px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#ffffff;">{{ $d['urgent_basis'] }}</p>
            @endif
            @if(!empty($d['urgent_note']))
                <p style="margin:8px 0;font-size:14px;line-height:1.55;color:#334155;">{{ $d['urgent_note'] }}</p>
            @endif
            @if(!empty($d['urgent_cta']))
                <p style="margin:0;font-size:14px;font-weight:800;color:#991b1b;">{{ $d['urgent_cta'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['bot_url']) || !empty($d['channel_url']) || !empty($d['web_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📲 {{ $d['connect_title'] ?? 'Stay Connected' }}</h3>
            @if(!empty($d['bot_url']) || !empty($d['bot_number']))
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
                <p style="margin:0 0 10px;">
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#128c7e;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_label'] ?? 'WhatsApp Channel' }}
                    </a>
                </p>
            @endif
            @if(!empty($d['web_url']))
                <p style="margin:0;">
                    <a href="{{ $d['web_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['web_label'] ?? 'Official Website' }}
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['closing_thanks']) || !empty($d['family']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#7f1d1d);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['closing_thanks']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['closing_thanks'] }}</p>
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
