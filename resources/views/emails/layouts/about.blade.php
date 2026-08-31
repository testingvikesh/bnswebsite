{{-- Front-style About BNS email layout --}}
@php
    $d = $data ?? [];
    $why = $d['why'] ?? [];
    $who = $d['who'] ?? [];
    $different = $d['different'] ?? [];
    $motto = $d['motto'] ?? [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'About BNS' }}
            </p>
            <h2 style="margin:0 0 8px;font-size:24px;line-height:1.3;font-weight:800;color:#ffffff;">
                {{ $d['brand'] ?? 'Business Navachar School (BNS)' }}
            </h2>
            <p style="margin:0;font-size:14px;line-height:1.55;color:rgba(255,255,255,0.85);">
                {{ $d['headline'] ?? '' }}
            </p>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;border-collapse:separate;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">
                    {{ $d['what_title'] ?? 'What is Business Navachar School (BNS)?' }}
                </span>
            </h3>
            <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#334155;">{{ $d['what_intro'] ?? '' }}</p>
            @if(!empty($d['what_focus']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid rgba(253,110,1,0.22);border-radius:12px;background:rgba(253,110,1,0.08);">
                    <tr>
                        <td style="padding:12px 14px;font-size:14px;line-height:1.65;font-weight:650;color:#0a1d37;">
                            {{ $d['what_focus'] }}
                        </td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>

@if($why !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;border-collapse:separate;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">
                    {{ $d['why_title'] ?? 'Why BNS?' }}
                </span>
            </h3>
            @foreach($why as $point)
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                    <tr>
                        <td width="36" valign="top" style="padding:10px 0 10px 12px;">
                            <div style="width:22px;height:22px;border-radius:999px;background:#dcfce7;text-align:center;line-height:22px;color:#16a34a;font-size:12px;font-weight:800;">✓</div>
                        </td>
                        <td style="padding:10px 12px 10px 8px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $point }}</td>
                    </tr>
                </table>
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($who !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;border-collapse:separate;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">
                    {{ $d['who_title'] ?? 'Who Can Join BNS?' }}
                </span>
            </h3>
            @foreach($who as $audience)
                <p style="margin:0 0 8px;padding:10px 12px;border-radius:12px;background:#fff7ed;border:1px solid #ffedd5;font-size:14px;font-weight:700;color:#0a1d37;">• {{ $audience }}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif

@if(!empty($d['mission']) || !empty($d['vision']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if(!empty($d['mission']))
        <td width="50%" valign="top" style="padding:0 6px 0 0;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;">
                <tr>
                    <td style="padding:16px;">
                        <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#fd6e01;">Mission</p>
                        <h4 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $d['mission_title'] ?? 'Our Mission' }}</h4>
                        <p style="margin:0;font-size:13px;line-height:1.65;color:#475569;">{{ $d['mission'] }}</p>
                    </td>
                </tr>
            </table>
        </td>
        @endif
        @if(!empty($d['vision']))
        <td width="50%" valign="top" style="padding:0 0 0 6px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;">
                <tr>
                    <td style="padding:16px;">
                        <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#2563eb;">Vision</p>
                        <h4 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $d['vision_title'] ?? 'Our Vision' }}</h4>
                        <p style="margin:0;font-size:13px;line-height:1.65;color:#475569;">{{ $d['vision'] }}</p>
                    </td>
                </tr>
            </table>
        </td>
        @endif
    </tr>
</table>
@endif

@if($different !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;border-collapse:separate;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">
                    {{ $d['different_title'] ?? 'What Makes BNS Different?' }}
                </span>
            </h3>
            @foreach($different as $point)
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                    <tr>
                        <td width="36" valign="top" style="padding:10px 0 10px 12px;">
                            <div style="width:22px;height:22px;border-radius:999px;background:#dcfce7;text-align:center;line-height:22px;color:#16a34a;font-size:12px;font-weight:800;">✓</div>
                        </td>
                        <td style="padding:10px 12px 10px 8px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $point }}</td>
                    </tr>
                </table>
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:16px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);border-collapse:separate;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['motto_title'] ?? 'Our Motto' }}</h3>
            @foreach($motto as $m)
                <p style="margin:0 0 6px;font-size:14px;font-weight:700;color:#ffffff;">
                    {{ is_array($m) ? (($m['icon'] ?? '').' '.($m['text'] ?? '')) : $m }}
                </p>
            @endforeach
        </td>
    </tr>
</table>
@endif

@if(!empty($d['about_url']) || !empty($d['website']))
<table role="presentation" cellspacing="0" cellpadding="0" style="margin:18px 0 0;">
    <tr>
        @if(!empty($d['about_url']))
        <td style="padding:0 8px 0 0;">
            <a href="{{ $d['about_url'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#fd6e01;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Read About BNS</a>
        </td>
        @endif
        @if(!empty($d['website']))
        <td>
            <a href="{{ $d['website'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Visit Website</a>
        </td>
        @endif
    </tr>
</table>
@endif
