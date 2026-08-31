@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Seminar Checklist');
    $headline = (string) ($d['headline'] ?? 'What to Bring?');
    $intro = (string) ($d['intro'] ?? '');
    $items = is_array($d['items'] ?? null) ? $d['items'] : [];
    $report = (string) ($d['report'] ?? '');
    $welcome = (string) ($d['welcome'] ?? '');
    $brand = (string) ($d['brand'] ?? '');
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

{{-- Teal hero matching web .bns-bring__hero --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#155e75 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22d3ee,#0e7490);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0 0 10px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🎒 {{ $headline }}</h2>
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
            $icon = is_array($row) ? (string) ($row['icon'] ?? '✅') : '✅';
            $title = is_array($row) ? (string) ($row['title'] ?? '') : '';
            $text = is_array($row) ? (string) ($row['text'] ?? '') : (string) $row;
            if ($title === '' && $text !== '') {
                $title = $text;
                $text = '';
            }
            $num = str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT);
        @endphp
        @if($title !== '' || $text !== '')
            <tr>
                <td style="padding:0 0 8px;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
                        <tr>
                            <td width="44" valign="middle" style="padding:12px 0 12px 12px;">
                                <div style="width:34px;height:34px;border-radius:10px;background:#ecfeff;text-align:center;line-height:34px;font-size:12px;font-weight:800;color:#0e7490;">{{ $num }}</div>
                            </td>
                            <td width="36" valign="middle" style="padding:12px 0;font-size:20px;text-align:center;">{{ $icon }}</td>
                            <td style="padding:12px 14px 12px 6px;">
                                @if($title !== '')
                                    <p style="margin:0 0 2px;font-size:14px;line-height:1.4;font-weight:800;color:#0a1d37;">{{ $title }}</p>
                                @endif
                                @if($text !== '')
                                    <p style="margin:0;font-size:13px;line-height:1.55;color:#64748b;">{{ $text }}</p>
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

@if($report !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:6px 0 0;border:1px solid #ffedd5;border-radius:14px;background:#fff7ed;">
    <tr>
        <td width="40" valign="top" style="padding:14px 0 14px 14px;font-size:18px;">⏰</td>
        <td style="padding:14px 14px 14px 4px;">
            <p style="margin:0 0 4px;font-size:13px;font-weight:800;color:#0a1d37;">Reporting Time</p>
            <p style="margin:0;font-size:14px;line-height:1.6;color:#475569;">{{ $report }}</p>
        </td>
    </tr>
</table>
@endif

@if($welcome !== '' || $brand !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;">
    <tr>
        <td style="padding:8px 4px;text-align:center;">
            @if($welcome !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#475569;">{{ $welcome }}</p>
            @endif
            @if($brand !== '')
                <p style="margin:0 0 10px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $brand }}</p>
            @endif
            @if($motto !== [])
                <table role="presentation" cellspacing="0" cellpadding="0" align="center" style="margin:0 auto;">
                    <tr>
                        @foreach($motto as $m)
                            @php
                                $mIcon = is_array($m) ? (string) ($m['icon'] ?? '✨') : '✨';
                                $mText = is_array($m) ? (string) ($m['text'] ?? '') : (string) $m;
                            @endphp
                            @if($mText !== '')
                                <td style="padding:4px 6px;">
                                    <span style="display:inline-block;padding:7px 10px;border-radius:999px;background:#f1f5f9;font-size:12px;font-weight:700;color:#0a1d37;white-space:nowrap;">{{ $mIcon }} {{ $mText }}</span>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif
