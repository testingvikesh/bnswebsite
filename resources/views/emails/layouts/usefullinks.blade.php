@php
    $d = is_array($data ?? null) ? $data : [];
    $links = is_array($d['links'] ?? null) ? $d['links'] : [];
    $social = is_array($d['social'] ?? null) ? $d['social'] : [];
    $venueLines = is_array($d['venue_lines'] ?? null) ? $d['venue_lines'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];

    $toneStyles = [
        'web' => ['border' => '#e5e7eb', 'bg' => '#f8fafc', 'btn' => '#0a1d37'],
        'register' => ['border' => '#ffedd5', 'bg' => '#fff7ed', 'btn' => '#ea580c'],
        'admit' => ['border' => '#ede9fe', 'bg' => '#faf5ff', 'btn' => '#7c3aed'],
        'pay' => ['border' => '#fecaca', 'bg' => '#fef2f2', 'btn' => '#dc2626'],
        'bot' => ['border' => '#bbf7d0', 'bg' => '#f0fdf4', 'btn' => '#16a34a'],
        'channel' => ['border' => '#a7f3d0', 'bg' => '#ecfdf5', 'btn' => '#0f766e'],
    ];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 55%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#38bdf8,#0284c7);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Save for Later' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🔗</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Important Useful Links' }}</h2>
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['intro']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['intro'] }}</p>
            @endif
        </td>
    </tr>
</table>

@foreach($links as $link)
    @php
        $tone = strtolower((string) ($link['tone'] ?? 'web'));
        $style = $toneStyles[$tone] ?? $toneStyles['web'];
        $title = trim((string) (($link['icon'] ?? '') !== '' ? ($link['icon'].' ') : '').($link['title'] ?? ''));
        $desc = (string) ($link['desc'] ?? '');
        $meta = (string) ($link['meta'] ?? '');
        $url = (string) ($link['url'] ?? '');
        $label = (string) ($link['label'] ?? 'Open');
    @endphp
    @if($title !== '' || $url !== '')
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid {{ $style['border'] }};border-radius:16px;background:{{ $style['bg'] }};">
            <tr>
                <td style="padding:18px;">
                    @if($title !== '')
                        <p style="margin:0 0 6px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $title }}</p>
                    @endif
                    @if($desc !== '')
                        <p style="margin:0 0 6px;font-size:13px;line-height:1.55;color:#475569;">{{ $desc }}</p>
                    @endif
                    @if($meta !== '')
                        <p style="margin:0 0 10px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $meta }}</p>
                    @endif
                    @if($url !== '')
                        <a href="{{ $url }}" style="display:inline-block;padding:10px 16px;border-radius:999px;background:{{ $style['btn'] }};color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                            {{ $label }} →
                        </a>
                    @endif
                </td>
            </tr>
        </table>
    @endif
@endforeach

@if($social !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🌐 {{ $d['social_title'] ?? 'Follow Us on Social Media' }}</h3>
            @foreach($social as $item)
                @php
                    $label = (string) ($item['label'] ?? '');
                    $url = (string) ($item['url'] ?? '');
                @endphp
                @if($label !== '' && $url !== '')
                    <p style="margin:0 0 8px;">
                        <a href="{{ $url }}" style="display:inline-block;padding:10px 14px;border-radius:999px;background:#f1f5f9;color:#0a1d37;font-size:13px;font-weight:700;text-decoration:none;">
                            {{ $label }}
                        </a>
                    </p>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($venueLines !== [] || !empty($d['map_url']))
@include('emails.layouts._ui-venue-simple', [
    'venue' => [
        'title' => $d['venue_title'] ?? 'Seminar Venue',
        'lines' => $venueLines,
        'maps_url' => $d['map_url'] ?? '',
    ],
])
@endif

@if(!empty($d['closing']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:18px;text-align:center;">
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
