@php
    $d = is_array($data ?? null) ? $data : [];
    $sections = is_array($d['sections'] ?? null) ? $d['sections'] : [];
    $helpLines = is_array($d['help_lines'] ?? null) ? $d['help_lines'] : [];
    $admitItems = is_array($d['admit_items'] ?? null) ? $d['admit_items'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7c2d12 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fb923c,#ea580c);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Seminar Day Guide' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">📢</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Seminar Instructions' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['intro'] }}</p>
            @endif
        </td>
    </tr>
</table>

@foreach($sections as $section)
    @php
        $sectionIcon = is_array($section) ? (string) ($section['icon'] ?? '📌') : '📌';
        $sectionTitle = is_array($section) ? (string) ($section['title'] ?? '') : '';
        $sectionItems = is_array($section['items'] ?? null) ? $section['items'] : [];
    @endphp
    @if($sectionTitle !== '' || $sectionItems !== [])
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
            <tr>
                <td style="padding:18px;">
                    <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $sectionIcon }} {{ $sectionTitle }}</h3>
                    @include('emails.layouts._checklist', ['items' => $sectionItems])
                </td>
            </tr>
        </table>
    @endif
@endforeach

@if($helpLines !== [] || !empty($d['help_title']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e0e7ff;border-radius:16px;background:#eef2ff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🙋 {{ $d['help_title'] ?? 'Need Any Help?' }}</h3>
            @foreach($helpLines as $line)
                <p style="margin:0 0 6px;font-size:14px;line-height:1.55;color:#334155;">{{ $line }}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🎓 {{ $d['admit_title'] ?? 'Admission Information' }}</h3>
            @if(!empty($d['admit_intro']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['admit_intro'] }}</p>
            @endif
            @if(!empty($d['admit_assist']))
                <p style="margin:0 0 10px;font-size:13px;font-weight:700;color:#9a3412;">{{ $d['admit_assist'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $admitItems])
        </td>
    </tr>
</table>

@if(!empty($d['bot_url']) || !empty($d['channel_url']) || !empty($d['web_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📲 {{ $d['connect_title'] ?? 'Stay Connected' }}</h3>
            @if(!empty($d['bot_url']) || !empty($d['bot_number']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 10px;border:1px solid #bbf7d0;border-radius:12px;background:#f0fdf4;">
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
            @endif
            @if(!empty($d['channel_url']))
                <p style="margin:0 0 10px;">
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#128c7e;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_label'] ?? 'WhatsApp Channel' }}
                    </a>
                </p>
            @endif
            @if(!empty($d['web_url']))
                <p style="margin:0;">
                    <a href="{{ $d['web_url'] }}" style="display:inline-block;padding:11px 16px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['web_label'] ?? 'Official Website' }}
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

@if(!empty($d['thanks']) || !empty($d['wish']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#7c2d12);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['wish']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['wish'] }}</p>
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
