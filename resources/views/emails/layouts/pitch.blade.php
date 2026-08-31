@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Share Links');
    $greeting = (string) ($d['greeting'] ?? "Dear Sir/Ma'am,");
    $thanks = (string) ($d['thanks'] ?? '');
    $intro = (string) ($d['intro'] ?? '');
    $links = is_array($d['links'] ?? null) ? $d['links'] : [];
    $closing = (string) ($d['closing'] ?? '');
    $signoff = (string) ($d['signoff'] ?? '');
    $brand = (string) ($d['brand'] ?? '');
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0 0 10px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🌟 {{ $greeting }}</h2>
            @if($thanks !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $thanks }}</p>
            @endif
        </td>
    </tr>
</table>

@if($intro !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <p style="margin:0;font-size:14px;line-height:1.7;color:#475569;">{{ $intro }}</p>
        </td>
    </tr>
</table>
@endif

@if($links !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;">
    @foreach($links as $link)
        @php
            $label = is_array($link) ? (string) ($link['label'] ?? 'Open link') : (string) $link;
            $hint = is_array($link) ? (string) ($link['hint'] ?? '') : '';
            $url = is_array($link) ? (string) ($link['url'] ?? '#') : '#';
            $icon = is_array($link) ? (string) ($link['icon'] ?? '🔗') : '🔗';
            $tone = is_array($link) ? (string) ($link['tone'] ?? 'web') : 'web';
            $btnBg = $tone === 'pitch' ? '#fd6e01' : '#0a1d37';
        @endphp
        @if($label !== '')
            <tr>
                <td style="padding:0 0 8px;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
                        <tr>
                            <td width="44" valign="middle" style="padding:14px 0 14px 14px;font-size:22px;line-height:1;">{{ $icon }}</td>
                            <td style="padding:14px 8px;">
                                <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $label }}</p>
                                @if($hint !== '')
                                    <p style="margin:4px 0 0;font-size:12px;line-height:1.45;font-style:italic;color:#64748b;">{{ $hint }}</p>
                                @endif
                            </td>
                            <td width="110" valign="middle" style="padding:14px 14px 14px 0;text-align:right;">
                                <a href="{{ $url }}" style="display:inline-block;padding:10px 14px;border-radius:999px;background:{{ $btnBg }};color:#ffffff;font-size:12px;font-weight:700;text-decoration:none;">Open →</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif
    @endforeach
</table>
@endif

@if($closing !== '' || $signoff !== '' || $brand !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if($closing !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#475569;">{{ $closing }}</p>
            @endif
            @if($signoff !== '')
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#0a1d37;">{{ $signoff }}</p>
            @endif
            @if($brand !== '')
                <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $brand }}</p>
            @endif
        </td>
    </tr>
</table>
@endif
