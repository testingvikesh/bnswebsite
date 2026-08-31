@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Vision & Mission');
    $headline = (string) ($d['headline'] ?? 'Our Vision & Mission');
    $brand = (string) ($d['brand'] ?? 'Business Navachar School (BNS)');
    $visionTitle = (string) ($d['vision_title'] ?? 'Our Vision');
    $visionText = (string) ($d['vision'] ?? '');
    $visionSupport = (string) ($d['vision_support'] ?? '');
    $missionTitle = (string) ($d['mission_title'] ?? 'Our Mission');
    $mission = is_array($d['mission'] ?? null) ? $d['mission'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

{{-- Matches web .bns-vision-msg --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 52%,#0f2744 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($brand !== '')
                <p style="margin:0;font-size:14px;line-height:1.55;color:rgba(255,255,255,0.88);">{{ $brand }}</p>
            @endif
        </td>
    </tr>
</table>

@if($visionText !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #dbeafe;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:16px;font-weight:800;color:#0a1d37;">🌍 {{ $visionTitle }}</h3>
            <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">{{ $visionText }}</p>
            @if($visionSupport !== '')
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:12px 0 0;border:1px solid #bfdbfe;border-radius:12px;background:#eff6ff;">
                    <tr>
                        <td width="36" valign="top" style="padding:12px 0 12px 12px;font-size:16px;">💻</td>
                        <td style="padding:12px 14px 12px 4px;font-size:13px;line-height:1.55;font-weight:700;color:#1e3a8a;">{{ $visionSupport }}</td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($mission !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #fecdd3;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">🎯 {{ $missionTitle }}</h3>
            @foreach($mission as $index => $point)
                @php
                    $text = is_array($point) ? (string) ($point['text'] ?? $point['label'] ?? '') : (string) $point;
                    $num = str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT);
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#fff7ed;">
                        <tr>
                            <td width="40" valign="middle" style="padding:10px 0 10px 12px;">
                                <div style="width:28px;height:28px;border-radius:999px;background:#fd6e01;text-align:center;line-height:28px;font-size:11px;font-weight:800;color:#ffffff;">{{ $num }}</div>
                            </td>
                            <td style="padding:10px 12px 10px 6px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @foreach($motto as $i => $m)
            @php
                $icon = is_array($m) ? (string) ($m['icon'] ?? '✨') : '✨';
                $text = is_array($m) ? (string) ($m['text'] ?? '') : (string) $m;
                $pad = ($i % 2 === 0) ? '0 6px 8px 0' : '0 0 8px 6px';
            @endphp
            @if($text !== '')
                <td width="50%" valign="top" style="padding:{{ $pad }};">
                    <table role="presentation" width="100%" style="border-radius:12px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
                        <tr>
                            <td style="padding:14px;text-align:center;color:#ffffff;">
                                <p style="margin:0 0 4px;font-size:18px;">{{ $icon }}</p>
                                <p style="margin:0;font-size:13px;font-weight:700;line-height:1.4;">{{ $text }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            @endif
            @if($i % 2 === 1 && $i < count($motto) - 1)
                </tr><tr>
            @endif
        @endforeach
        @if(count($motto) % 2 === 1)
            <td width="50%" style="padding:0 0 8px 6px;"></td>
        @endif
    </tr>
</table>
@endif

@if(!empty($d['vision_url']) || !empty($d['mission_url']) || !empty($d['website']))
<p style="margin:16px 0 0;text-align:center;">
    @if(!empty($d['vision_url']))
        <a href="{{ $d['vision_url'] }}" style="display:inline-block;margin:0 4px 8px;padding:11px 16px;border-radius:999px;background:#2563eb;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">👁 Our Vision</a>
    @endif
    @if(!empty($d['mission_url']))
        <a href="{{ $d['mission_url'] }}" style="display:inline-block;margin:0 4px 8px;padding:11px 16px;border-radius:999px;background:#fd6e01;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">🎯 Our Mission</a>
    @endif
    @if(!empty($d['website']))
        <a href="{{ $d['website'] }}" style="display:inline-block;margin:0 4px 8px;padding:11px 16px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">🌐 Visit Website</a>
    @endif
</p>
@endif
