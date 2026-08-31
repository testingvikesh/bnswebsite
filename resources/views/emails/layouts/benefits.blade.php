@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Why Join BNS');
    $headline = (string) ($d['headline'] ?? 'Benefits of Joining BNS');
    $question = (string) ($d['question'] ?? '');
    $answer = (string) ($d['answer'] ?? '');
    $listTitle = (string) ($d['list_title'] ?? 'You will get:');
    $items = is_array($d['items'] ?? null) ? $d['items'] : [];
    $sessionTitle = (string) ($d['session_title'] ?? '');
    $sessionIntro = (string) ($d['session_intro'] ?? '');
    $sessionPoints = is_array($d['session_points'] ?? null) ? $d['session_points'] : [];
    $ctaText = (string) ($d['cta_text'] ?? '');
    $welcome = (string) ($d['welcome'] ?? '');
    $registerUrl = (string) ($d['register_url'] ?? '');
    $registerLabel = (string) ($d['register_label'] ?? 'Book FREE Session');
    $website = (string) ($d['website'] ?? '');
    $brand = (string) ($d['brand'] ?? '');
    $tagline = (string) ($d['tagline'] ?? '');
@endphp

{{-- Green hero matching web .bns-benefits__hero --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#14532d 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0;font-size:22px;line-height:1.35;font-weight:800;color:#ffffff;">🌟 {{ $headline }}</h2>
        </td>
    </tr>
</table>

@if($question !== '' || $answer !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($question !== '')
                <p style="margin:0 0 10px;font-size:16px;line-height:1.45;font-weight:800;color:#0a1d37;">{{ $question }}</p>
            @endif
            @if($answer !== '')
                <p style="margin:0;font-size:14px;line-height:1.7;color:#475569;">{{ $answer }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($items !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(22,163,74,0.45);">{{ $listTitle }}</span>
            </h3>
            @foreach($items as $point)
                @php
                    $text = is_array($point) ? (string) ($point['text'] ?? $point['label'] ?? '') : (string) $point;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #dcfce7;border-radius:12px;background:#f0fdf4;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;">
                                <div style="width:22px;height:22px;border-radius:999px;background:#dcfce7;text-align:center;line-height:22px;color:#15803d;font-size:12px;font-weight:800;">✓</div>
                            </td>
                            <td style="padding:10px 12px 10px 8px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($sessionTitle !== '' || $sessionPoints !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($sessionTitle !== '')
                <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $sessionTitle }}</span>
                </h3>
            @endif
            @if($sessionIntro !== '')
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $sessionIntro }}</p>
            @endif
            @foreach($sessionPoints as $point)
                @php
                    $text = is_array($point) ? (string) ($point['text'] ?? $point['label'] ?? '') : (string) $point;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="28" valign="top" style="padding:10px 0 10px 12px;">
                                <div style="width:8px;height:8px;margin-top:6px;border-radius:999px;background:#fd6e01;"></div>
                            </td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.5;font-weight:650;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($ctaText !== '' || $welcome !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:14px;background:#fff7ed;">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($ctaText !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;font-weight:700;color:#0a1d37;">{{ $ctaText }}</p>
            @endif
            @if($welcome !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:#475569;">{{ $welcome }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($registerUrl !== '' || $website !== '')
<p style="margin:16px 0 0;text-align:center;">
    @if($registerUrl !== '')
        <a href="{{ $registerUrl }}" style="display:inline-block;margin:0 6px 8px;padding:12px 18px;border-radius:999px;background:#15803d;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
            {{ $registerLabel }}
        </a>
    @endif
    @if($website !== '')
        <a href="{{ $website }}" style="display:inline-block;margin:0 6px 8px;padding:12px 18px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
            Visit Website
        </a>
    @endif
</p>
@endif

@if($brand !== '' || $tagline !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:16px 0 0;">
    <tr>
        <td style="padding:8px 4px;text-align:center;">
            @if($brand !== '')
                <p style="margin:0 0 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $brand }}</p>
            @endif
            @if($tagline !== '')
                <p style="margin:0;font-size:13px;line-height:1.55;font-style:italic;color:#64748b;">“{{ $tagline }}”</p>
            @endif
        </td>
    </tr>
</table>
@endif
