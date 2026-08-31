@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Action Needed');
    $headline = (string) ($d['headline'] ?? 'Admission Reminder');
    $greeting = (string) ($d['greeting'] ?? '');
    $thanks = (string) ($d['thanks'] ?? '');
    $request = (string) ($d['request'] ?? '');
    $opportunity = (string) ($d['opportunity'] ?? '');
    $benefits = is_array($d['benefits'] ?? null) ? $d['benefits'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">⏳</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($thanks !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $thanks }}</p>
            @endif
            @if($request !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $request }}</p>
            @endif
            @if($opportunity !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $opportunity }}</p>
            @endif
        </td>
    </tr>
</table>

@if($benefits !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🎯 {{ $d['benefits_title'] ?? 'Admission Benefits' }}</h3>
            @include('emails.layouts._checklist', ['items' => $benefits])
        </td>
    </tr>
</table>
@endif

@if(!empty($d['complete']) || !empty($d['portal_url']) || !empty($d['pay_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['complete']))
                <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">🎓 {{ $d['complete'] }}</h3>
            @endif
            @if(!empty($d['portal_url']))
                <a href="{{ $d['portal_url'] }}" style="display:inline-block;margin:0 6px 8px;padding:12px 18px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['portal_label'] ?? 'Admission Portal' }}
                </a>
            @endif
            @if(!empty($d['pay_url']))
                <a href="{{ $d['pay_url'] }}" style="display:inline-block;margin:0 6px 8px;padding:12px 18px;border-radius:999px;background:#dc2626;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['pay_label'] ?? 'Pay Online' }}
                </a>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['bot_number']) || !empty($d['bot_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">❓ {{ $d['assist_title'] ?? 'Need Assistance?' }}</h3>
            <table role="presentation" width="100%" style="border:1px solid #bbf7d0;border-radius:12px;background:#f0fdf4;">
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
        </td>
    </tr>
</table>
@endif

@if(!empty($d['urgency']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#7f1d1d);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if(!empty($d['urgency']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;font-weight:700;color:#ffffff;">{{ $d['urgency'] }}</p>
            @endif
            @foreach($motto as $line)
                <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#ffffff;">{{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif
