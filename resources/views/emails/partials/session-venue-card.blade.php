@php
    $venueCard = $venueCard ?? bns_intro_session_venue_card($event ?? []);
    $address = $venueCard['address'] ?? [];
    $mapsUrl = $address['maps_url'] ?? '';
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:16px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:#dc2626;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $venueCard['eyebrow'] ?? 'Event Location' }}
            </p>
            <h2 style="margin:0 0 10px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">
                📍 {{ $venueCard['headline'] ?? 'Venue, Date, Time & Location' }}
            </h2>
            <p style="margin:0;font-size:14px;line-height:1.6;color:rgba(255,255,255,0.88);">
                {{ $venueCard['intro'] ?? '' }}
            </p>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 12px;border-collapse:separate;">
    <tr>
        <td width="50%" valign="top" style="padding:0 6px 0 0;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #ffd4cc;border-radius:14px;background:#fff8f7;">
                <tr>
                    <td style="padding:16px;">
                        <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#94a3b8;">📅 Date</p>
                        <p style="margin:0;font-size:15px;font-weight:800;line-height:1.4;color:#0a1d37;">{{ $venueCard['date'] ?? '' }}</p>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%" valign="top" style="padding:0 0 0 6px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #ffd4cc;border-radius:14px;background:#fff8f7;">
                <tr>
                    <td style="padding:16px;">
                        <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#94a3b8;">🕘 Time</p>
                        <p style="margin:0;font-size:15px;font-weight:800;line-height:1.4;color:#0a1d37;">{{ $venueCard['time'] ?? '' }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #ffd4cc;border-radius:14px;background:#fff8f7;border-collapse:separate;">
    <tr>
        <td style="padding:18px;">
            <p style="margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #fecaca;font-size:15px;font-weight:800;color:#b91c1c;">📍 Venue</p>
            @if(!empty($address['title']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;line-height:1.45;color:#0a1d37;">{{ $address['title'] }}</p>
            @endif
            @foreach(($address['lines'] ?? []) as $line)
                <p style="margin:0 0 4px;font-size:14px;line-height:1.55;color:#475569;">{{ $line }}</p>
            @endforeach
            @if($mapsUrl !== '')
                <p style="margin:14px 0 0;">
                    <a href="{{ $mapsUrl }}" style="display:inline-block;padding:10px 16px;border-radius:999px;background:#dc2626;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        Open GPS Location
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>
