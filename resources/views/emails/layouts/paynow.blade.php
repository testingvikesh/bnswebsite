@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Secure Checkout');
    $headline = (string) ($d['headline'] ?? 'Complete Your Admission Payment');
    $greeting = (string) ($d['greeting'] ?? '');
    $thanks = (string) ($d['thanks'] ?? '');
    $intro = (string) ($d['intro'] ?? '');
    $payTitle = (string) ($d['pay_title'] ?? 'Payment Link');
    $payLabel = (string) ($d['pay_label'] ?? 'Pay Now');
    $payUrl = (string) ($d['pay_url'] ?? '');
    $perks = is_array($d['perks'] ?? null) ? $d['perks'] : [];
    $assistTitle = (string) ($d['assist_title'] ?? 'Need Assistance?');
    $botLabel = (string) ($d['bot_label'] ?? 'WhatsApp BOT');
    $botNumber = (string) ($d['bot_number'] ?? '');
    $botHint = (string) ($d['bot_hint'] ?? '');
    $botUrl = (string) ($d['bot_url'] ?? '');
    $urgency = (string) ($d['urgency'] ?? '');
    $family = (string) ($d['family'] ?? '');
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#0f766e 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#14b8a6,#0f766e);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">💳</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($thanks !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $thanks }}</p>
            @endif
            @if($intro !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $intro }}</p>
            @endif
        </td>
    </tr>
</table>

@if($payUrl !== '' || $payTitle !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ccfbf1;border-radius:16px;background:#f0fdfa;">
    <tr>
        <td style="padding:22px;text-align:center;">
            <p style="margin:0 0 6px;font-size:22px;line-height:1;">🔒</p>
            <h3 style="margin:0 0 14px;font-size:16px;font-weight:800;color:#0a1d37;">{{ $payTitle }}</h3>
            @if($payUrl !== '')
                <a href="{{ $payUrl }}" style="display:inline-block;padding:14px 28px;border-radius:999px;background:#0f766e;color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;">
                    💳 {{ $payLabel }}
                </a>
            @endif
        </td>
    </tr>
</table>
@endif

@if($perks !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @include('emails.layouts._checklist', ['items' => $perks])
        </td>
    </tr>
</table>
@endif

@if($botNumber !== '' || $botUrl !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">❓ {{ $assistTitle }}</h3>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #bbf7d0;border-radius:14px;background:#f0fdf4;">
                <tr>
                    <td style="padding:16px;">
                        <p style="margin:0 0 4px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">{{ $botLabel }}</p>
                        @if($botNumber !== '')
                            <p style="margin:0 0 6px;font-size:20px;font-weight:800;color:#0a1d37;">{{ $botNumber }}</p>
                        @endif
                        @if($botHint !== '')
                            <p style="margin:0 0 12px;font-size:13px;line-height:1.5;color:#64748b;">{{ $botHint }}</p>
                        @endif
                        @if($botUrl !== '')
                            <a href="{{ $botUrl }}" style="display:inline-block;padding:11px 18px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Message BOT</a>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif

@if($urgency !== '' || $family !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#0f766e);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($urgency !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;font-weight:800;color:#fde68a;">{{ $urgency }}</p>
            @endif
            @if($family !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;font-weight:700;color:#ffffff;">{{ $family }}</p>
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
