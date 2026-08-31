@php
    $d = is_array($data ?? null) ? $data : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $journeys = is_array($d['journeys'] ?? null) ? $d['journeys'] : [];
    $partners = is_array($d['partners'] ?? null) ? $d['partners'] : [];
    $tagline = is_array($d['tagline'] ?? null) ? $d['tagline'] : [];
    $register = is_array($d['register'] ?? null) ? $d['register'] : [];
    $reel = is_array($d['reel'] ?? null) ? $d['reel'] : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $venueLines = is_array($venue['lines'] ?? null) ? $venue['lines'] : [];
    $website = is_string($d['website'] ?? null) ? $d['website'] : '';
    $websiteLabel = $website !== '' ? preg_replace('#^https?://#', '', $website) : '';
@endphp

{{-- Hero (matches web .bns-promo__hero) --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#f87171,#dc2626);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'FREE Introduction Seminar' }}
            </p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['brand'] ?? 'Business Navachar School (BNS)' }}</h2>
            <p style="margin:0 0 14px;font-size:14px;color:rgba(255,255,255,0.88);">{{ $d['hero_sub'] ?? 'Two free sessions · Limited seats · Registration required' }}</p>
            <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.04em;color:rgba(255,255,255,0.82);">
                📅 2 Sessions &nbsp;·&nbsp; 📍 Santacruz West &nbsp;·&nbsp; 🎟 Free Entry
            </p>
        </td>
    </tr>
</table>

{{-- Intro card --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffd4cc;border-radius:16px;background:#fff8f7;">
    <tr>
        <td style="padding:18px;">
            <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $d['greeting'] ?? 'Dear Participant,' }}</p>
            @if(!empty($d['intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#334155;">{{ $d['intro'] }}</p>
            @endif
            @if(!empty($d['highlight']) || !empty($d['highlight_title']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid rgba(220,38,38,0.22);border-radius:12px;background:rgba(220,38,38,0.08);">
                    <tr>
                        <td style="padding:12px 14px;">
                            @if(!empty($d['highlight_title']))
                                <p style="margin:0 0 4px;font-size:13px;font-weight:800;color:#dc2626;">{{ $d['highlight_title'] }}</p>
                            @endif
                            @if(!empty($d['highlight']))
                                <p style="margin:0;font-size:14px;line-height:1.65;color:#0a1d37;">{{ $d['highlight'] }}</p>
                            @endif
                        </td>
                    </tr>
                </table>
            @endif
            @if(!empty($d['opportunity']))
                <p style="margin:12px 0 0;font-size:14px;line-height:1.7;color:#334155;">{{ $d['opportunity'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($journeys !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">Learning Journeys</span>
            </h3>
            @foreach($journeys as $journey)
                @php
                    $icon = is_array($journey) ? (string) ($journey['icon'] ?? '✨') : '✨';
                    $text = is_array($journey) ? (string) ($journey['text'] ?? '') : (string) $journey;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;">
                                <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">Learn the Journey from</p>
                                <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $text }}</p>
                            </td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if(!empty($reel['url']) || !empty($register['url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if(!empty($reel['url']))
            <td width="{{ !empty($register['url']) ? '50%' : '100%' }}" valign="top" style="padding:0 {{ !empty($register['url']) ? '6px' : '0' }} 0 0;">
                <a href="{{ $reel['url'] }}" style="display:block;padding:14px 16px;border-radius:14px;background:linear-gradient(135deg,#833ab4,#fd1d1d,#fcb045);color:#ffffff;text-decoration:none;font-size:13px;font-weight:800;text-align:center;">
                    ▶ Watch<br>{{ $reel['label'] ?? 'Introduction Reel' }}
                </a>
            </td>
        @endif
        @if(!empty($register['url']))
            <td width="{{ !empty($reel['url']) ? '50%' : '100%' }}" valign="top" style="padding:0 0 0 {{ !empty($reel['url']) ? '6px' : '0' }};">
                <a href="{{ $register['url'] }}" style="display:block;padding:14px 16px;border-radius:14px;background:#dc2626;color:#ffffff;text-decoration:none;font-size:13px;font-weight:800;text-align:center;">
                    📝 Register<br>{{ $register['label'] ?? 'Register Now' }}
                </a>
            </td>
        @endif
    </tr>
</table>
@endif

@if($sessions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">Choose Your Preferred Session</span>
            </h3>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    @foreach($sessions as $index => $session)
                        <td width="50%" valign="top" style="padding:{{ $index === 0 ? '0 6px 0 0' : '0 0 0 6px' }};">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #ffd4cc;border-radius:14px;background:#fff8f7;">
                                <tr>
                                    <td style="padding:14px;">
                                        <p style="margin:0 0 6px;font-size:18px;">{{ $session['emoji'] ?? '📅' }}</p>
                                        <p style="margin:0 0 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $session['label'] ?? ('Session '.($index + 1)) }}</p>
                                        <p style="margin:0 0 2px;font-size:13px;color:#475569;">📅 {{ $session['date'] ?? '' }}</p>
                                        <p style="margin:0;font-size:13px;font-weight:700;color:#0a1d37;">🕘 {{ $session['time'] ?? '' }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    @endforeach
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif

@if(!empty($venue['title']) || $venueLines !== [] || !empty($venue['maps_url']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">Venue &amp; GPS</span>
            </h3>
            @if(!empty($venue['title']))
                <p style="margin:0 0 8px;font-size:14px;font-weight:800;line-height:1.45;color:#0a1d37;">{{ $venue['title'] }}</p>
            @endif
            @foreach($venueLines as $line)
                <p style="margin:0 0 2px;font-size:13px;line-height:1.55;color:#475569;">{{ $line }}</p>
            @endforeach
            @if(!empty($venue['maps_url']))
                <p style="margin:14px 0 0;">
                    <a href="{{ $venue['maps_url'] }}" style="display:inline-block;padding:10px 16px;border-radius:999px;background:rgba(37,99,235,0.1);color:#1d4ed8;font-size:13px;font-weight:800;text-decoration:none;">
                        📍 Open GPS Location
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

@if($website !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px dashed #cbd5e1;border-radius:14px;background:#ffffff;">
    <tr>
        <td style="padding:14px;text-align:center;">
            <a href="{{ $website }}" style="color:#1d4ed8;font-size:14px;font-weight:800;text-decoration:none;">🌐 {{ $websiteLabel }}</a>
        </td>
    </tr>
</table>
@endif

{{-- Footer / closing (matches web .bns-promo__footer) --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:16px;background:linear-gradient(180deg,#fff5f5,#ffffff);border:1px solid #ffd4cc;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['badge']))
                <p style="margin:0 0 12px;display:inline-block;padding:8px 14px;border-radius:999px;background:#fff;border:1px solid #fecaca;color:#dc2626;font-size:12px;font-weight:800;">
                    {{ $d['badge'] }}
                </p>
            @endif
            @if(!empty($d['closing']))
                <p style="margin:0 0 14px;font-size:14px;line-height:1.7;color:#334155;">{{ $d['closing'] }}</p>
            @endif
            @if($tagline !== [])
                <p style="margin:0;">
                    @foreach($tagline as $line)
                        <span style="display:inline-block;margin:0 4px 8px;padding:7px 12px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:12px;font-weight:800;">
                            {{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}
                        </span>
                    @endforeach
                </p>
            @endif
        </td>
    </tr>
</table>
