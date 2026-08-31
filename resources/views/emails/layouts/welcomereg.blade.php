@php
    $d = is_array($data ?? null) ? $data : [];
    $steps = is_array($d['steps'] ?? null) ? $d['steps'] : [];
    $attendance = is_array($d['attendance'] ?? null) ? $d['attendance'] : [];
    $after = is_array($d['after'] ?? null) ? $d['after'] : [];
    $instructions = is_array($d['instructions'] ?? null) ? $d['instructions'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    $websiteHost = ! empty($d['website']) ? preg_replace('#^https?://#', '', (string) $d['website']) : '';
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#0f766e 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#2dd4bf,#0f766e);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Check-In Process' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">📲</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Welcome Registration Process' }}</h2>
            @if(!empty($d['intro']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['request']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['request'] }}</p>
            @endif
            @if(!empty($d['duration']))
                <p style="margin:0;display:inline-block;padding:8px 14px;border-radius:999px;background:rgba(255,255,255,0.15);font-size:13px;font-weight:800;color:#ffffff;">{{ $d['duration'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($steps !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📝 {{ $d['steps_title'] ?? 'How to Complete Your Welcome Registration' }}</h3>
            @foreach($steps as $index => $step)
                @php $num = str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT); @endphp
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #ccfbf1;border-radius:12px;background:#f0fdfa;">
                    <tr>
                        <td width="40" valign="top" style="padding:10px 0 10px 12px;">
                            <div style="width:28px;height:28px;border-radius:8px;background:#ccfbf1;text-align:center;line-height:28px;font-size:11px;font-weight:800;color:#0f766e;">{{ $num }}</div>
                        </td>
                        <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $step }}</td>
                    </tr>
                </table>
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($attendance !== [] || !empty($d['attendance_intro']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:16px;background:#f0fdf4;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">✅ {{ $d['attendance_title'] ?? 'Online Attendance' }}</h3>
            @if(!empty($d['attendance_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['attendance_intro'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $attendance])
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e0e7ff;border-radius:16px;background:#eef2ff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🙋 {{ $d['help_title'] ?? 'Need Any Help?' }}</h3>
            @if(!empty($d['help_intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['help_intro'] }}</p>
            @endif
            @if(!empty($d['help']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['help'] }}</p>
            @endif
            @if(!empty($d['help_note']))
                <p style="margin:0;font-size:14px;line-height:1.55;color:#64748b;">{{ $d['help_note'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($after !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📍 {{ $d['after_title'] ?? 'After Registration' }}</h3>
            @include('emails.layouts._checklist', ['items' => $after])
        </td>
    </tr>
</table>
@endif

@if($instructions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📢 {{ $d['instructions_title'] ?? 'Important Instructions' }}</h3>
            @foreach($instructions as $row)
                @php
                    $icon = is_array($row) ? (string) ($row['icon'] ?? '📌') : '📌';
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

@if(!empty($d['bot_url']) || !empty($d['website']) || !empty($d['channel_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📲 {{ $d['assist_title'] ?? 'Need Additional Assistance?' }}</h3>
            @if(!empty($d['bot_url']))
                <p style="margin:0 0 10px;">
                    <a href="{{ $d['bot_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['bot_label'] ?? 'WhatsApp BOT' }}@if(!empty($d['bot_hint'])) — {{ $d['bot_hint'] }}@endif
                    </a>
                </p>
            @endif
            @if(!empty($d['website']))
                <p style="margin:0 0 10px;">
                    <a href="{{ $d['website'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        🌐 {{ $websiteHost }}
                    </a>
                </p>
            @endif
            @if(!empty($d['channel_url']))
                <p style="margin:0;">
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#128c7e;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_title'] ?? 'WhatsApp Channel' }}
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

@if(!empty($d['thanks']) || !empty($d['closing']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#0f766e);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['closing']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['closing'] }}</p>
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
