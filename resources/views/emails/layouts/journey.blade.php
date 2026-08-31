@php
    $d = is_array($data ?? null) ? $data : [];
    $topics = is_array($d['topics'] ?? null) ? $d['topics'] : [];
    $sessions = is_array($d['sessions'] ?? null) ? $d['sessions'] : [];
    $venue = is_array($d['venue'] ?? null) ? $d['venue'] : [];
    $tagline = $d['tagline'] ?? '';
    if (is_array($tagline)) {
        $tagline = collect($tagline)->map(fn ($l) => is_array($l) ? trim(($l['icon'] ?? '').' '.($l['text'] ?? '')) : (string) $l)->filter()->implode(' ');
    }
@endphp

{{-- Hero --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 55%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Learning Roadmap' }}
            </p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">🚀 {{ $d['headline'] ?? 'Business ABCD to IPO Journey' }}</h2>
            @if(!empty($d['hook']))
                <p style="margin:0;font-size:14px;line-height:1.55;color:rgba(255,255,255,0.88);">{{ $d['hook'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['intro']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <p style="margin:0;font-size:14px;line-height:1.7;color:#334155;">{{ $d['intro'] }}</p>
        </td>
    </tr>
</table>
@endif

@include('emails.layouts._ui-topics', [
    'topics' => $topics,
    'topicsTitle' => $d['learn_title'] ?? 'What Will You Learn?',
])

@include('emails.layouts._ui-sessions', [
    'sessions' => $sessions,
    'sessionsTitle' => $d['session_title'] ?? 'Join Our FREE Introduction Session',
])

@include('emails.layouts._ui-venue-simple', ['venue' => $venue])

@if(!empty($d['register_url']) || !empty($d['website']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-collapse:separate;">
    <tr>
        @if(!empty($d['register_url']))
            <td width="{{ !empty($d['website']) ? '50%' : '100%' }}" style="padding:0 {{ !empty($d['website']) ? '6px' : '0' }} 0 0;">
                <a href="{{ $d['register_url'] }}" style="display:block;padding:14px 16px;border-radius:14px;background:#dc2626;color:#ffffff;text-align:center;font-size:13px;font-weight:800;text-decoration:none;">
                    🎟 {{ $d['register_label'] ?? 'Book Your Seat Today' }}
                </a>
            </td>
        @endif
        @if(!empty($d['website']))
            <td width="{{ !empty($d['register_url']) ? '50%' : '100%' }}" style="padding:0 0 0 {{ !empty($d['register_url']) ? '6px' : '0' }};">
                <a href="{{ $d['website'] }}" style="display:block;padding:14px 16px;border-radius:14px;background:#0a1d37;color:#ffffff;text-align:center;font-size:13px;font-weight:800;text-decoration:none;">
                    🌐 Visit Website
                </a>
            </td>
        @endif
    </tr>
</table>
@endif

@if(!empty($d['brand']) || $tagline !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:16px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['brand']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['brand'] }}</p>
            @endif
            @if($tagline !== '')
                <p style="margin:0;font-size:13px;line-height:1.6;color:rgba(255,255,255,0.88);font-style:italic;">“{{ $tagline }}”</p>
            @endif
        </td>
    </tr>
</table>
@endif
