@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Gratitude');
    $headline = (string) ($d['headline'] ?? 'Thank You');
    $greeting = (string) ($d['greeting'] ?? 'Dear Participant,');
    $intro = (string) ($d['intro'] ?? '');
    $appreciation = (string) ($d['appreciation'] ?? '');
    $growth = (string) ($d['growth'] ?? '');
    $insightsTitle = (string) ($d['insights_title'] ?? 'You will gain insights into:');
    $insights = is_array($d['insights'] ?? null) ? $d['insights'] : [];
    $calendarNote = (string) ($d['calendar_note'] ?? '');
    $botTitle = (string) ($d['bot_title'] ?? 'BNS WhatsApp BOT');
    $botNumber = (string) ($d['bot_number'] ?? '');
    $botHint = (string) ($d['bot_hint'] ?? '');
    $botUrl = (string) ($d['bot_url'] ?? '');
    $channelTitle = (string) ($d['channel_title'] ?? 'WhatsApp Channel');
    $channelUrl = (string) ($d['channel_url'] ?? '');
    $website = (string) ($d['website'] ?? '');
    $closingThanks = (string) ($d['closing_thanks'] ?? '');
    $closing = (string) ($d['closing'] ?? '');
    $seeYou = (string) ($d['see_you'] ?? '');
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#14532d 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🌟 {{ $headline }}</h2>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($greeting !== '')
                <p style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $greeting }}</p>
            @endif
            @if($intro !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#475569;">{{ $intro }}</p>
            @endif
            @if($appreciation !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#475569;">{{ $appreciation }}</p>
            @endif
            @if($growth !== '')
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #dcfce7;border-radius:12px;background:#f0fdf4;">
                    <tr>
                        <td width="36" valign="top" style="padding:14px 0 14px 14px;font-size:18px;">🌱</td>
                        <td style="padding:14px 14px 14px 4px;font-size:14px;line-height:1.65;color:#0a1d37;">{{ $growth }}</td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>

@if($insights !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(22,163,74,0.45);">{{ $insightsTitle }}</span>
            </h3>
            @include('emails.layouts._checklist', ['items' => $insights])
        </td>
    </tr>
</table>
@endif

@if($calendarNote !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:14px;background:#fff7ed;">
    <tr>
        <td width="36" valign="top" style="padding:16px 0 16px 16px;font-size:18px;">📅</td>
        <td style="padding:16px 16px 16px 4px;font-size:14px;line-height:1.65;font-weight:650;color:#0a1d37;">{{ $calendarNote }}</td>
    </tr>
</table>
@endif

@if($botUrl !== '' || $botNumber !== '' || $channelUrl !== '' || $website !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($botUrl !== '' || $botNumber !== '')
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 12px;border:1px solid #bbf7d0;border-radius:14px;background:#f0fdf4;">
                    <tr>
                        <td style="padding:16px;">
                            <p style="margin:0 0 4px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">{{ $botTitle }}</p>
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
            @endif
            <p style="margin:0;text-align:center;">
                @if($channelUrl !== '')
                    <a href="{{ $channelUrl }}" style="display:inline-block;margin:0 6px 8px;padding:12px 18px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $channelTitle }}
                    </a>
                @endif
                @if($website !== '')
                    <a href="{{ $website }}" style="display:inline-block;margin:0 6px 8px;padding:12px 18px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        Official Website
                    </a>
                @endif
            </p>
        </td>
    </tr>
</table>
@endif

@if($closingThanks !== '' || $closing !== '' || $seeYou !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#14532d);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($closingThanks !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;font-weight:700;color:#ffffff;">{{ $closingThanks }}</p>
            @endif
            @if($closing !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $closing }}</p>
            @endif
            @if($seeYou !== '')
                <p style="margin:0 0 10px;font-size:15px;font-weight:800;color:#ffffff;">{{ $seeYou }}</p>
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
