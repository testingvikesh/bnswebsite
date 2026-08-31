@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'After Seminar');
    $headline = (string) ($d['headline'] ?? 'Previous Seminar Photo Gallery');
    $greeting = (string) ($d['greeting'] ?? '');
    $intro = (string) ($d['intro'] ?? '');
    $glimpse = (string) ($d['glimpse'] ?? '');
    $galleryTitle = (string) ($d['gallery_title'] ?? 'Previous Seminar Photo Gallery');
    $galleryLabel = (string) ($d['gallery_label'] ?? 'Open Photo Gallery');
    $galleryUrl = (string) ($d['gallery_url'] ?? '');
    $reelTitle = (string) ($d['reel_title'] ?? 'Please Also Watch Our Official Reel');
    $reelIntro = (string) ($d['reel_intro'] ?? '');
    $reelRequest = (string) ($d['reel_request'] ?? '');
    $reelPoints = is_array($d['reel_points'] ?? null) ? $d['reel_points'] : [];
    $reelLabel = (string) ($d['reel_label'] ?? 'Watch Official BNS Reel');
    $reelUrl = (string) ($d['reel_url'] ?? '');
    $inspire = (string) ($d['inspire'] ?? '');
    $thanks = (string) ($d['thanks'] ?? '');
    $family = (string) ($d['family'] ?? '');
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#4c1d95 48%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#a78bfa,#7c3aed);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">📸</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($intro !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $intro }}</p>
            @endif
            @if($glimpse !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $glimpse }}</p>
            @endif
        </td>
    </tr>
</table>

@if($galleryUrl !== '' || $galleryTitle !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:22px;text-align:center;">
            <p style="margin:0 0 8px;font-size:28px;line-height:1;">📷</p>
            <h3 style="margin:0 0 14px;font-size:16px;font-weight:800;color:#0a1d37;">{{ $galleryTitle }}</h3>
            @if($galleryUrl !== '')
                <a href="{{ $galleryUrl }}" style="display:inline-block;padding:12px 20px;border-radius:999px;background:#7c3aed;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $galleryLabel }}
                </a>
            @endif
        </td>
    </tr>
</table>
@endif

@if($reelTitle !== '' || $reelPoints !== [] || $reelUrl !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🎥 {{ $reelTitle }}</h3>
            @if($reelIntro !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#475569;">{{ $reelIntro }}</p>
            @endif
            @if($reelRequest !== '')
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;font-weight:650;color:#0a1d37;">{{ $reelRequest }}</p>
            @endif
            @if($reelPoints !== [])
                @include('emails.layouts._checklist', ['items' => $reelPoints])
            @endif
            @if($reelUrl !== '')
                <p style="margin:14px 0 0;text-align:center;">
                    <a href="{{ $reelUrl }}" style="display:inline-block;padding:12px 20px;border-radius:999px;background:linear-gradient(135deg,#ec4899,#db2777);color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                        {{ $reelLabel }}
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($inspire !== '' || $thanks !== '' || $family !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#4c1d95);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($inspire !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $inspire }}</p>
            @endif
            @if($thanks !== '')
                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#ffffff;">{{ $thanks }}</p>
            @endif
            @if($family !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $family }}</p>
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
