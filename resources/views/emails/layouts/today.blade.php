@php
    $d = is_array($data ?? null) ? $data : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $leave = is_array($d['leave'] ?? null) ? $d['leave'] : [];
    $carry = is_array($d['carry'] ?? null) ? $d['carry'] : [];
    $dress = is_array($d['dress'] ?? null) ? $d['dress'] : [];
    $learn = is_array($d['learn'] ?? null) ? $d['learn'] : [];
    $instructions = is_array($d['instructions'] ?? null) ? $d['instructions'] : [];
    $reg = is_array($d['reg'] ?? null) ? $d['reg'] : [];
    $network = is_array($d['network'] ?? null) ? $d['network'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Seminar Day' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🏁</p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🌟 {{ $d['headline'] ?? 'Today Is The Day!' }}</h2>
            @if(!empty($d['greeting']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['greeting'] }}</p>
            @endif
            @if(!empty($d['hook']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:rgba(255,255,255,0.92);">{{ $d['hook'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0 0 6px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['welcome']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $d['welcome'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['date']) || !empty($d['time']) || !empty($d['report_time']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📅 Seminar Details</h3>
            @if(!empty($d['date']))
                <p style="margin:0 0 8px;font-size:14px;color:#0a1d37;"><strong>Date:</strong> {{ $d['date'] }}</p>
            @endif
            @if(!empty($d['time']))
                <p style="margin:0 0 8px;font-size:14px;color:#0a1d37;"><strong>Seminar Time:</strong> {{ $d['time'] }}</p>
            @endif
            @if(!empty($d['report_time']))
                <p style="margin:0;font-size:14px;font-weight:700;color:#dc2626;"><strong>Reporting Time:</strong> {{ $d['report_time'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@include('emails.layouts._ui-venue-simple', ['venue' => $venue])

@if($leave !== [] || !empty($d['seats']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🚗 {{ $d['leave_title'] ?? 'Before You Leave' }}</h3>
            @include('emails.layouts._checklist', ['items' => $leave])
            @if(!empty($d['seats']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:10px 0 0;border:1px solid #fed7aa;border-radius:12px;background:#ffffff;">
                    <tr>
                        <td width="36" valign="middle" style="padding:12px 0 12px 12px;font-size:16px;">💺</td>
                        <td style="padding:12px 12px 12px 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['seats'] }}</td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($carry !== [] || $dress !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if($carry !== [])
            <td width="{{ $dress !== [] ? '50%' : '100%' }}" valign="top" style="padding:0 {{ $dress !== [] ? '6px' : '0' }} 0 0;">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
                    <tr>
                        <td style="padding:16px;">
                            <h3 style="margin:0 0 12px;font-size:14px;font-weight:800;color:#0a1d37;">🎒 {{ $d['carry_title'] ?? 'Please Carry' }}</h3>
                            @include('emails.layouts._checklist', ['items' => $carry])
                        </td>
                    </tr>
                </table>
            </td>
        @endif
        @if($dress !== [])
            <td width="{{ $carry !== [] ? '50%' : '100%' }}" valign="top" style="padding:0 0 0 {{ $carry !== [] ? '6px' : '0' }};">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
                    <tr>
                        <td style="padding:16px;">
                            <h3 style="margin:0 0 12px;font-size:14px;font-weight:800;color:#0a1d37;">👔 {{ $d['dress_title'] ?? 'Dress Code' }}</h3>
                            @include('emails.layouts._checklist', ['items' => $dress])
                        </td>
                    </tr>
                </table>
            </td>
        @endif
    </tr>
</table>
@endif

@if($learn !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🎯 {{ $d['learn_title'] ?? "Today's Learning" }}</h3>
            @foreach($learn as $note)
                @php
                    $icon = is_array($note) ? (string) ($note['icon'] ?? '📌') : '📌';
                    $text = is_array($note) ? (string) ($note['text'] ?? '') : (string) $note;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:650;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($instructions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📢 {{ $d['instructions_title'] ?? 'Important Instructions' }}</h3>
            @foreach($instructions as $note)
                @php
                    $icon = is_array($note) ? (string) ($note['icon'] ?? '📌') : '📌';
                    $text = is_array($note) ? (string) ($note['text'] ?? '') : (string) $note;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:650;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($reg !== [] || $network !== [] || !empty($d['network_note']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if($reg !== [])
            <td width="{{ ($network !== [] || !empty($d['network_note'])) ? '50%' : '100%' }}" valign="top" style="padding:0 {{ ($network !== [] || !empty($d['network_note'])) ? '6px' : '0' }} 0 0;">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
                    <tr>
                        <td style="padding:16px;">
                            <h3 style="margin:0 0 12px;font-size:14px;font-weight:800;color:#0a1d37;">✅ {{ $d['reg_title'] ?? 'Registration' }}</h3>
                            @include('emails.layouts._checklist', ['items' => $reg])
                        </td>
                    </tr>
                </table>
            </td>
        @endif
        @if($network !== [] || !empty($d['network_note']))
            <td width="{{ $reg !== [] ? '50%' : '100%' }}" valign="top" style="padding:0 0 0 {{ $reg !== [] ? '6px' : '0' }};">
                <table role="presentation" width="100%" style="border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
                    <tr>
                        <td style="padding:16px;">
                            <h3 style="margin:0 0 12px;font-size:14px;font-weight:800;color:#0a1d37;">🤝 {{ $d['network_title'] ?? 'Network & Connect' }}</h3>
                            @include('emails.layouts._checklist', ['items' => $network])
                            @if(!empty($d['network_note']))
                                <p style="margin:10px 0 0;font-size:13px;line-height:1.6;font-style:italic;color:#64748b;">{{ $d['network_note'] }}</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        @endif
    </tr>
</table>
@endif

@if(!empty($d['bot_url']) || !empty($d['channel_url']) || !empty($d['website']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📲 {{ $d['help_title'] ?? 'Need Any Help?' }}</h3>
            @if(!empty($d['bot_url']))
                <a href="{{ $d['bot_url'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:12px 14px;border-radius:12px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['bot_label'] ?? 'WhatsApp BOT' }}@if(!empty($d['bot_hint'])) — {{ $d['bot_hint'] }}@endif
                </a>
            @endif
            @if(!empty($d['channel_url']))
                <a href="{{ $d['channel_url'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:12px 14px;border-radius:12px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['channel_title'] ?? 'WhatsApp Channel' }}
                </a>
            @endif
            @if(!empty($d['website']))
                <a href="{{ $d['website'] }}" style="display:inline-block;margin:0 0 8px 0;padding:12px 14px;border-radius:12px;background:#fd6e01;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ preg_replace('#^https?://#', '', (string) $d['website']) }}
                </a>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['rsvp']) || !empty($d['rsvp_intro']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:16px;background:#f0fdf4;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">✅ {{ $d['rsvp_title'] ?? 'Attendance Confirmation' }}</h3>
            @if(!empty($d['rsvp_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['rsvp_intro'] }}</p>
            @endif
            @if(!empty($d['rsvp']))
                <p style="margin:0 0 10px;display:inline-block;padding:12px 18px;border-radius:999px;background:#16a34a;font-size:14px;font-weight:800;color:#ffffff;">🚗 {{ is_array($d['rsvp']) ? implode(' / ', $d['rsvp']) : $d['rsvp'] }}</p>
            @endif
            @if(!empty($d['rsvp_note']))
                <p style="margin:10px 0 0;font-size:13px;font-style:italic;color:#64748b;">{{ $d['rsvp_note'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#f8fafc;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $d['final_title'] ?? 'Final Message' }}</h3>
            @if(!empty($d['final']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $d['final'] }}</p>
            @endif
            @if(!empty($d['final_note']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['final_note'] }}</p>
            @endif
            @if(!empty($d['final_tagline']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#fd6e01;">{{ $d['final_tagline'] }}</p>
            @endif
            @if(!empty($d['safe']))
                <p style="margin:0 0 8px;font-size:14px;font-style:italic;color:#475569;">{{ $d['safe'] }}</p>
            @endif
            @if(!empty($d['closing']))
                <p style="margin:0;font-size:14px;line-height:1.65;font-weight:700;color:#0a1d37;">{{ $d['closing'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @foreach($motto as $line)
                <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#ffffff;">
                    {{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}
                </p>
            @endforeach
        </td>
    </tr>
</table>
@endif
