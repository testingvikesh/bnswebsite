@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Stay Connected');
    $headline = (string) ($d['headline'] ?? 'Join Our Official WhatsApp Channel');
    $greeting = (string) ($d['greeting'] ?? '');
    $intro = (string) ($d['intro'] ?? '');
    $benefitsTitle = (string) ($d['benefits_title'] ?? 'You will receive:');
    $benefits = is_array($d['benefits'] ?? null) ? $d['benefits'] : [];
    $channelTitle = (string) ($d['channel_title'] ?? 'Official WhatsApp Channel');
    $channelUrl = (string) ($d['channel_url'] ?? '');
    $joinNote = (string) ($d['join_note'] ?? '');
    $botTitle = (string) ($d['bot_title'] ?? 'Need More Information?');
    $botIntro = (string) ($d['bot_intro'] ?? '');
    $botNumber = (string) ($d['bot_number'] ?? '');
    $botListTitle = (string) ($d['bot_list_title'] ?? '');
    $botFeatures = is_array($d['bot_features'] ?? null) ? $d['bot_features'] : [];
    $botUrl = (string) ($d['bot_url'] ?? '');
    $website = (string) ($d['website'] ?? '');
    $closing = (string) ($d['closing'] ?? '');
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    $showBot = $botNumber !== '' || $botUrl !== '' || $botIntro !== '';
    $websiteLabel = $website !== '' ? preg_replace('#^https?://#i', '', $website) : '';
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#14532d 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">📢</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($intro !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $intro }}</p>
            @endif
        </td>
    </tr>
</table>

@if($benefits !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(22,163,74,0.45);">{{ $benefitsTitle }}</span>
            </h3>
            @include('emails.layouts._checklist', ['items' => $benefits])
        </td>
    </tr>
</table>
@endif

@if($channelUrl !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:16px;background:#f0fdf4;">
    <tr>
        <td style="padding:20px;text-align:center;">
            <p style="margin:0 0 4px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">Tap to Join</p>
            <p style="margin:0 0 14px;font-size:16px;font-weight:800;color:#0a1d37;">{{ $channelTitle }}</p>
            <a href="{{ $channelUrl }}" style="display:inline-block;padding:14px 24px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;">
                Join WhatsApp Channel
            </a>
        </td>
    </tr>
</table>
@endif

@if($joinNote !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:14px;background:#fff7ed;">
    <tr>
        <td style="padding:16px;text-align:center;">
            <p style="margin:0;font-size:14px;line-height:1.65;font-weight:650;color:#0a1d37;">{{ $joinNote }}</p>
        </td>
    </tr>
</table>
@endif

@if($showBot)
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $botTitle }}</h3>
            @if($botIntro !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:#475569;">{{ $botIntro }}</p>
            @endif
            @if($botNumber !== '')
                <p style="margin:0 0 10px;font-size:20px;font-weight:800;color:#0a1d37;">{{ $botNumber }}</p>
            @endif
            @if($botListTitle !== '')
                <p style="margin:0 0 10px;font-size:14px;font-weight:700;color:#334155;">{{ $botListTitle }}</p>
            @endif
            @foreach($botFeatures as $feature)
                @php $text = is_array($feature) ? (string) ($feature['text'] ?? $feature['label'] ?? '') : (string) $feature; @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="28" valign="top" style="padding:10px 0 10px 12px;">
                                <div style="width:8px;height:8px;margin-top:6px;border-radius:999px;background:#16a34a;"></div>
                            </td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:650;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
            @if($botUrl !== '')
                <p style="margin:12px 0 0;">
                    <a href="{{ $botUrl }}" style="display:inline-block;padding:11px 18px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Message BOT</a>
                </p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($website !== '')
<p style="margin:16px 0 0;text-align:center;">
    <a href="{{ $website }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
        {{ $websiteLabel }}
    </a>
</p>
@endif

@if($closing !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#14532d);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($closing !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:#ffffff;">{{ $closing }}</p>
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
