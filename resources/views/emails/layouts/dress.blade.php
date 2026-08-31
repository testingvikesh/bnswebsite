@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Seminar Guidelines');
    $headline = (string) ($d['headline'] ?? 'Dress Code');
    $greeting = (string) ($d['greeting'] ?? '');
    $intro = (string) ($d['intro'] ?? '');
    $recommendedTitle = (string) ($d['recommended_title'] ?? 'Recommended Dress Code');
    $recommended = is_array($d['recommended'] ?? null) ? $d['recommended'] : [];
    $avoidTitle = (string) ($d['avoid_title'] ?? 'Kindly Avoid');
    $avoid = is_array($d['avoid'] ?? null) ? $d['avoid'] : [];
    $whyTitle = (string) ($d['why_title'] ?? 'Why Dress Professionally?');
    $whyIntro = (string) ($d['why_intro'] ?? '');
    $why = is_array($d['why'] ?? null) ? $d['why'] : [];
    $thanks = (string) ($d['thanks'] ?? '');
    $closing = (string) ($d['closing'] ?? '');
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">👔</p>
            <h2 style="margin:0 0 10px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($intro !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $intro }}</p>
            @endif
        </td>
    </tr>
</table>

@if($recommended !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">✅ {{ $recommendedTitle }}</h3>
            @foreach($recommended as $point)
                @php $text = is_array($point) ? (string) ($point['text'] ?? $point['label'] ?? '') : (string) $point; @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #dcfce7;border-radius:12px;background:#f0fdf4;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;">
                                <div style="width:22px;height:22px;border-radius:999px;background:#dcfce7;text-align:center;line-height:22px;color:#16a34a;font-size:12px;font-weight:800;">✓</div>
                            </td>
                            <td style="padding:10px 12px 10px 8px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($avoid !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #fecaca;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🚫 {{ $avoidTitle }}</h3>
            @foreach($avoid as $point)
                @php $text = is_array($point) ? (string) ($point['text'] ?? $point['label'] ?? '') : (string) $point; @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #fee2e2;border-radius:12px;background:#fef2f2;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;">
                                <div style="width:22px;height:22px;border-radius:999px;background:#fee2e2;text-align:center;line-height:22px;color:#dc2626;font-size:12px;font-weight:800;">✕</div>
                            </td>
                            <td style="padding:10px 12px 10px 8px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($whyIntro !== '' || $why !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">💼 {{ $whyTitle }}</h3>
            @if($whyIntro !== '')
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $whyIntro }}</p>
            @endif
            @foreach($why as $point)
                @php $text = is_array($point) ? (string) ($point['text'] ?? $point['label'] ?? '') : (string) $point; @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="28" valign="top" style="padding:10px 0 10px 12px;">
                                <div style="width:8px;height:8px;margin-top:6px;border-radius:999px;background:#fd6e01;"></div>
                            </td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:650;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($thanks !== '' || $closing !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($thanks !== '')
                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#ffffff;">{{ $thanks }}</p>
            @endif
            @if($closing !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $closing }}</p>
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
