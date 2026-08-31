@php
    $d = is_array($data ?? null) ? $data : [];
    $learn = is_array($d['learn'] ?? null) ? $d['learn'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Must Watch' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🎥</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Watch Our FREE Introduction Session' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['designed']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.86);">{{ $d['designed'] }}</p>
            @endif
            @if(!empty($d['clarity']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.82);">{{ $d['clarity'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($learn !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🎓 {{ $d['learn_title'] ?? 'What You Will Learn' }}</h3>
            @include('emails.layouts._checklist', ['items' => $learn])
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #fecaca;border-radius:16px;background:#fef2f2;">
    <tr>
        <td style="padding:22px;text-align:center;">
            <p style="margin:0 0 8px;font-size:28px;line-height:1;">▶️</p>
            <h3 style="margin:0 0 14px;font-size:16px;font-weight:800;color:#0a1d37;">{{ $d['watch_title'] ?? 'FREE Introduction Session' }}</h3>
            @if(!empty($d['watch_url']))
                <a href="{{ $d['watch_url'] }}" style="display:inline-block;padding:14px 22px;border-radius:999px;background:#dc2626;color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;">
                    {{ $d['watch_label'] ?? 'Watch Here' }}
                </a>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['request']) || !empty($d['benefit']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;">
            @if(!empty($d['request']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['request'] }}</p>
            @endif
            @if(!empty($d['benefit']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:#475569;">{{ $d['benefit'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['family']) || !empty($d['thanks']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#7f1d1d);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['family']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['family'] }}</p>
            @endif
            @if(!empty($d['thanks']))
                <p style="margin:0 0 12px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['thanks'] }}</p>
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
