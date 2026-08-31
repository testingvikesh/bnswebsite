@php
    $d = is_array($data ?? null) ? $data : [];
    $awaits = is_array($d['awaits'] ?? null) ? $d['awaits'] : [];
    $before = is_array($d['before'] ?? null) ? $d['before'] : [];
    $participate = is_array($d['participate'] ?? null) ? $d['participate'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $thoughtActions = is_array($d['thought_actions'] ?? null) ? $d['thought_actions'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    $websiteHost = ! empty($d['website']) ? preg_replace('#^https?://#', '', (string) $d['website']) : '';
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#b45309 48%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fbbf24,#d97706);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Seminar Welcome' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🌞</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Good Morning & Welcome!' }}</h2>
            @if(!empty($d['intro']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['journey']))
                <p style="margin:0;font-size:14px;line-height:1.65;font-weight:800;color:#fde68a;">{{ $d['journey'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($awaits !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $d['awaits_title'] ?? 'What Awaits You Today?' }}</h3>
            @foreach($awaits as $row)
                @php
                    $icon = is_array($row) ? (string) ($row['icon'] ?? '✨') : '✨';
                    $text = is_array($row) ? (string) ($row['text'] ?? '') : (string) $row;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #fef3c7;border-radius:12px;background:#fffbeb;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($before !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📢 {{ $d['before_title'] ?? 'Before We Begin' }}</h3>
            @foreach($before as $row)
                @php
                    $icon = is_array($row) ? (string) ($row['icon'] ?? '✅') : '✅';
                    $text = is_array($row) ? (string) ($row['text'] ?? '') : (string) $row;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($participate !== [] || !empty($d['participate_note']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">✍️ {{ $d['participate_title'] ?? 'Participate Actively' }}</h3>
            @foreach($participate as $row)
                @php
                    $icon = is_array($row) ? (string) ($row['icon'] ?? '✨') : '✨';
                    $text = is_array($row) ? (string) ($row['text'] ?? '') : (string) $row;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
            @if(!empty($d['participate_note']))
                <p style="margin:12px 0 0;padding:12px 14px;border-radius:12px;background:#fff7ed;border:1px solid #ffedd5;font-size:14px;line-height:1.55;font-weight:700;color:#9a3412;">{{ $d['participate_note'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['photo']) || !empty($d['photo_note']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e0e7ff;border-radius:16px;background:#eef2ff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">📸 {{ $d['photo_title'] ?? 'During the Seminar' }}</h3>
            @if(!empty($d['photo']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['photo'] }}</p>
            @endif
            @if(!empty($d['photo_note']))
                <p style="margin:0;font-size:13px;line-height:1.65;color:#64748b;">{{ $d['photo_note'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['bot_url']) || !empty($d['channel_url']) || !empty($d['website']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📲 {{ $d['help_title'] ?? 'Stay Connected' }}</h3>
            @if(!empty($d['bot_url']))
                <p style="margin:0 0 10px;">
                    <a href="{{ $d['bot_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['bot_label'] ?? 'WhatsApp BOT' }}@if(!empty($d['bot_hint'])) — {{ $d['bot_hint'] }}@endif
                    </a>
                </p>
            @endif
            @if(!empty($d['channel_url']))
                <p style="margin:0 0 10px;">
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#128c7e;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_title'] ?? 'WhatsApp Channel' }}
                    </a>
                </p>
            @endif
            @if(!empty($d['website']))
                <p style="margin:0;">
                    <a href="{{ $d['website'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        🌐 {{ $websiteHost }}
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($partners !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @foreach($partners as $i => $partner)
            <td width="50%" valign="top" style="padding:{{ $i % 2 === 0 ? '0 6px 0 0' : '0 0 0 6px' }};">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                    <tr>
                        <td style="padding:14px;">
                            <p style="margin:0 0 6px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">{{ $partner['label'] ?? 'Partner' }}</p>
                            <p style="margin:0;font-size:13px;font-weight:800;line-height:1.45;color:#0a1d37;">{{ $partner['name'] ?? '' }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        @endforeach
    </tr>
</table>
@endif

@if(!empty($d['thought_title']) || !empty($d['thought_quote']) || !empty($d['thought']) || $thoughtActions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #fde68a;border-radius:16px;background:#fffbeb;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <p style="margin:0 0 10px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#b45309;">{{ $d['thought_title'] ?? "Today's Thought" }}</p>
            @if(!empty($d['thought_quote']))
                <p style="margin:0 0 10px;font-size:16px;line-height:1.5;font-weight:800;font-style:italic;color:#0a1d37;">“{{ $d['thought_quote'] }}”</p>
            @endif
            @if(!empty($d['thought']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['thought'] }}</p>
            @endif
            @foreach($thoughtActions as $action)
                <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#92400e;">{{ $action }}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif

@if(!empty($d['wish']) || !empty($d['closing']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['wish']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['wish'] }}</p>
            @endif
            @if(!empty($d['closing']))
                <p style="margin:0 0 12px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['closing'] }}</p>
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
