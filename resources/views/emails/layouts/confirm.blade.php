@php
    $d = $data ?? [];
    $bring = $d['bring'] ?? [];
    $venue = $d['venue'] ?? [];
    $sessions = $d['sessions'] ?? [];
    $instructions = $d['instructions'] ?? [];
    $motto = $d['motto'] ?? [];
@endphp

{{-- Hero --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#16a34a;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Confirmed' }}
            </p>
            <h2 style="margin:0;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🌟 {{ $d['headline'] ?? 'Registration Confirmation' }}</h2>
        </td>
    </tr>
</table>

{{-- Greeting + confirmation status --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #bbf7d0;border-radius:14px;background:#f0fdf4;">
    <tr>
        <td style="padding:18px;">
            <p style="margin:0 0 10px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['greeting'] ?? 'Dear Participant,' }}</p>
            @if(!empty($d['thanks']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#334155;">{{ $d['thanks'] }}</p>
            @endif
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 12px;border:1px solid #86efac;border-radius:12px;background:#ffffff;">
                <tr>
                    <td style="padding:14px;">
                        <p style="margin:0 0 6px;font-size:15px;font-weight:800;color:#166534;">🎉 {{ $d['congrats'] ?? ($d['confirmed'] ?? 'Congratulations!') }}</p>
                        <p style="margin:0;font-size:14px;line-height:1.65;font-weight:700;color:#0a1d37;">
                            {{ $d['status'] ?? $d['intro'] ?? 'Your registration has been successfully confirmed, and your seat has been reserved.' }}
                        </p>
                    </td>
                </tr>
            </table>
            @if(!empty($d['welcome']))
                <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">{{ $d['welcome'] }}</p>
            @endif
        </td>
    </tr>
</table>

{{-- Seminar details: sessions array OR date/time/reporting fields --}}
@if($sessions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
    <tr>
        <td style="padding:16px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(22,163,74,0.45);">📅 Seminar Details</span>
            </h3>
            @foreach($sessions as $session)
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 10px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                    <tr>
                        <td style="padding:12px 14px;">
                            <p style="margin:0 0 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ ($session['emoji'] ?? '').' '.($session['label'] ?? '') }}</p>
                            <p style="margin:0;font-size:13px;line-height:1.55;color:#475569;">
                                {{ $session['date'] ?? '' }}
                                @if(!empty($session['time'])) · {{ $session['time'] }}@endif
                                @if(!empty($session['reporting'])) · Report: {{ $session['reporting'] }}@endif
                            </p>
                        </td>
                    </tr>
                </table>
            @endforeach
        </td>
    </tr>
</table>
@elseif(!empty($d['date']) || !empty($d['time']) || !empty($d['reporting']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
    <tr>
        <td style="padding:16px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(22,163,74,0.45);">📅 Seminar Details</span>
            </h3>
            @if(!empty($d['date']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.55;color:#0a1d37;"><strong>Date:</strong> {{ $d['date'] }}</p>
            @endif
            @if(!empty($d['time']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.55;color:#0a1d37;"><strong>Seminar Time:</strong> {{ $d['time'] }}</p>
            @endif
            @if(!empty($d['reporting']))
                <p style="margin:0;font-size:14px;line-height:1.55;color:#0a1d37;"><strong>Reporting Time:</strong> {{ $d['reporting'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

{{-- Venue --}}
@if(!empty($venue))
@include('emails.partials.session-venue-card', [
    'venueCard' => [
        'eyebrow' => 'Event Location',
        'headline' => 'Venue, Date, Time & Location',
        'intro' => 'Your confirmed seminar venue details are below.',
        'date' => $d['date'] ?? '',
        'time' => $d['time'] ?? '',
        'address' => [
            'title' => $venue['title'] ?? '',
            'lines' => $venue['lines'] ?? [],
            'maps_url' => $venue['maps_url'] ?? '',
        ],
    ],
])
@endif

{{-- Please bring --}}
@if($bring !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">🎒 {{ $d['bring_title'] ?? 'Please Bring Along' }}</span>
            </h3>
            @include('emails.layouts._checklist', ['items' => $bring])
        </td>
    </tr>
</table>
@endif

{{-- Important instructions --}}
@if($instructions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">📢 {{ $d['instructions_title'] ?? 'Important Instructions' }}</span>
            </h3>
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

{{-- Invite --}}
@if(!empty($d['invite']) || !empty($d['invite_title']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:14px;background:#fff7ed;">
    <tr>
        <td style="padding:16px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">👨‍👩‍👧 {{ $d['invite_title'] ?? 'Invite Your Family & Friends' }}</h3>
            <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">{{ $d['invite'] ?? '' }}</p>
        </td>
    </tr>
</table>
@endif

{{-- Stay connected --}}
@if(!empty($d['bot_number']) || !empty($d['bot_url']) || !empty($d['channel_url']) || !empty($d['website']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
    <tr>
        <td style="padding:16px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🤖 Stay Connected</h3>

            @if(!empty($d['bot_number']) || !empty($d['bot_url']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 12px;border:1px solid #bbf7d0;border-radius:12px;background:#f0fdf4;">
                    <tr>
                        <td style="padding:14px;">
                            <p style="margin:0 0 4px;font-size:13px;font-weight:800;color:#166534;">{{ $d['bot_title'] ?? 'BNS WhatsApp BOT' }}</p>
                            @if(!empty($d['bot_number']))
                                <p style="margin:0 0 4px;font-size:18px;font-weight:800;color:#0a1d37;">📲 {{ $d['bot_number'] }}</p>
                            @endif
                            @if(!empty($d['bot_hint']))
                                <p style="margin:0 0 10px;font-size:13px;color:#475569;">{{ $d['bot_hint'] }}</p>
                            @endif
                            @if(!empty($d['bot_url']))
                                <a href="{{ $d['bot_url'] }}" style="display:inline-block;padding:10px 14px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Message BOT</a>
                            @endif
                        </td>
                    </tr>
                </table>
            @endif

            <p style="margin:0;">
                @if(!empty($d['channel_url']))
                    <a href="{{ $d['channel_url'] }}" style="display:inline-block;margin:0 8px 8px 0;padding:10px 14px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $d['channel_title'] ?? 'Join WhatsApp Channel' }}
                    </a>
                @endif
                @if(!empty($d['website']))
                    <a href="{{ $d['website'] }}" style="display:inline-block;margin:0 0 8px 0;padding:10px 14px;border-radius:999px;background:#fd6e01;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        Official Website
                    </a>
                @endif
            </p>
        </td>
    </tr>
</table>
@endif

{{-- Closing --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;">
    <tr>
        <td style="padding:16px;">
            @if(!empty($d['calendar_note']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#334155;">📅 {{ $d['calendar_note'] }}</p>
            @endif
            @if(!empty($d['help']))
                <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#334155;">❓ {{ $d['help'] }}</p>
            @endif
            @if(!empty($d['closing_thanks']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#0a1d37;">🙏 {{ $d['closing_thanks'] }}</p>
            @endif
            @if(!empty($d['closing']))
                <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">{{ $d['closing'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @foreach($motto as $line)
                <p style="margin:0 0 6px;font-size:14px;font-weight:700;color:#ffffff;">
                    {{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}
                </p>
            @endforeach
        </td>
    </tr>
</table>
@endif
