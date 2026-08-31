@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Watch & Share');
    $headline = (string) ($d['headline'] ?? 'Introduction Reels');
    $intro = (string) ($d['intro'] ?? '');
    $items = is_array($d['items'] ?? null) ? $d['items'] : [];
    $closing = (string) ($d['closing'] ?? '');
    $brand = (string) ($d['brand'] ?? '');
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#7f1d1d 48%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#f43f5e,#be123c);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0 0 10px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🎬 {{ $headline }}</h2>
            @if($intro !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $intro }}</p>
            @endif
        </td>
    </tr>
</table>

@if($items !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;">
    @foreach($items as $index => $row)
        @php
            $label = is_array($row) ? (string) ($row['label'] ?? ('Introduction Reel '.((int) $index + 1))) : (string) $row;
            $hint = is_array($row) ? (string) ($row['hint'] ?? '') : '';
            $url = is_array($row) ? trim((string) ($row['url'] ?? '')) : '';
            $num = str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT);
            $pending = $url === '';
        @endphp
        @if($label !== '')
            <tr>
                <td style="padding:0 0 8px;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:14px;background:{{ $pending ? '#f8fafc' : '#ffffff' }};">
                        <tr>
                            <td width="40" valign="middle" style="padding:14px 0 14px 14px;">
                                <div style="width:32px;height:32px;border-radius:10px;background:#fee2e2;text-align:center;line-height:32px;font-size:12px;font-weight:800;color:#be123c;">{{ $num }}</div>
                            </td>
                            <td width="36" valign="middle" style="padding:14px 0;font-size:14px;color:#be123c;text-align:center;">▶</td>
                            <td style="padding:14px 8px;">
                                <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $label }}</p>
                                @if($hint !== '')
                                    <p style="margin:4px 0 0;font-size:12px;line-height:1.45;font-style:italic;color:#64748b;">{{ $hint }}</p>
                                @endif
                            </td>
                            <td width="100" valign="middle" style="padding:14px 14px 14px 0;text-align:right;">
                                @if(!$pending)
                                    <a href="{{ $url }}" style="display:inline-block;padding:10px 14px;border-radius:999px;background:#be123c;color:#ffffff;font-size:12px;font-weight:700;text-decoration:none;">Watch →</a>
                                @else
                                    <span style="display:inline-block;padding:10px 14px;border-radius:999px;background:#e2e8f0;color:#64748b;font-size:12px;font-weight:700;">Soon</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif
    @endforeach
</table>
@endif

@if($closing !== '' || $brand !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#7f1d1d);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($closing !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#ffffff;">{{ $closing }}</p>
            @endif
            @if($brand !== '')
                <p style="margin:0;font-size:14px;font-weight:800;color:#ffffff;">{{ $brand }}</p>
            @endif
        </td>
    </tr>
</table>
@endif
