@php
    $d = is_array($data ?? null) ? $data : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    $address = is_array($d['address'] ?? null) ? $d['address'] : [];
    if ($address === [] && (! empty($d['title']) || ! empty($d['lines']))) {
        $address = [
            'title' => $d['title'] ?? '',
            'lines' => $d['lines'] ?? [],
            'maps_url' => $d['maps_url'] ?? '',
        ];
    }
    // Prefer nested venue.lines when address empty but venue structured like journey
    if (empty($address['lines']) && ! empty($d['lines']) && is_array($d['lines'])) {
        $address['lines'] = $d['lines'];
        $address['title'] = $address['title'] ?? ($d['venue_title'] ?? 'Venue');
        $address['maps_url'] = $address['maps_url'] ?? ($d['maps_url'] ?? '');
    }
@endphp

{{-- Gradient hero like web / Gmail screenshot --}}
@include('emails.partials.session-venue-card', [
    'venueCard' => [
        'eyebrow' => $d['eyebrow'] ?? 'Event Location',
        'headline' => $d['headline'] ?? 'Venue Details',
        'intro' => $d['intro'] ?? '',
        'date' => $d['date'] ?? ($sessions[0]['date'] ?? ''),
        'time' => $d['time'] ?? ($sessions[0]['time'] ?? ''),
        'address' => $address,
    ],
])

@if($sessions !== [])
    @include('emails.layouts._ui-sessions', [
        'sessions' => $sessions,
        'sessionsTitle' => $d['sessions_title'] ?? 'Choose Your Session',
    ])
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

@if(!empty($d['badge']))
<p style="margin:14px 0 0;text-align:center;">
    <span style="display:inline-block;padding:8px 14px;border-radius:999px;background:#fff5f3;color:#dc2626;font-size:13px;font-weight:700;">{{ $d['badge'] }}</span>
</p>
@endif

@if(!empty($d['register_url']))
<p style="margin:16px 0 0;text-align:center;">
    <a href="{{ $d['register_url'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#dc2626;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
        {{ $d['register_label'] ?? 'Register Today' }}
    </a>
</p>
@endif

@if(!empty($d['closing']))
<p style="margin:16px 0 0;font-size:14px;line-height:1.7;color:#334155;text-align:center;">{{ $d['closing'] }}</p>
@endif

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
