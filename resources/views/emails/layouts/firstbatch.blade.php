@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Founding Batch');
    $headline = (string) ($d['headline'] ?? 'Welcome to the First Batch');
    $greeting = (string) ($d['greeting'] ?? '');
    $congrats = (string) ($d['congrats'] ?? '');
    $community = (string) ($d['community'] ?? '');
    $founding = (string) ($d['founding'] ?? '');
    $journeyTitle = (string) ($d['journey_title'] ?? 'Your Journey Begins Here');
    $journey = is_array($d['journey'] ?? null) ? $d['journey'] : [];
    $commitment = (string) ($d['commitment'] ?? '');
    $together = (string) ($d['together'] ?? '');
    $family = (string) ($d['family'] ?? '');
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#92400e 48%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fbbf24,#d97706);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🎉</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($congrats !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;font-weight:700;color:rgba(255,255,255,0.95);">{{ $congrats }}</p>
            @endif
            @if($community !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $community }}</p>
            @endif
            @if($founding !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $founding }}</p>
            @endif
        </td>
    </tr>
</table>

@if($journey !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $journeyTitle }}</h3>
            @include('emails.layouts._checklist', ['items' => $journey])
        </td>
    </tr>
</table>
@endif

@if($commitment !== '' || $together !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if($commitment !== '')
                <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#475569;">{{ $commitment }}</p>
            @endif
            @if($together !== '')
                <p style="margin:0;font-size:15px;line-height:1.65;font-weight:800;color:#0a1d37;">{{ $together }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($family !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#92400e);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($family !== '')
                <p style="margin:0 0 10px;font-size:16px;font-weight:800;color:#ffffff;">🎓 {{ $family }}</p>
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
