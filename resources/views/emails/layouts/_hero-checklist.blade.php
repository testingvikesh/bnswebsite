@php
    $d = $data ?? [];
    $eyebrow = $d['eyebrow'] ?? 'BNS';
    $headline = $d['headline'] ?? $d['brand'] ?? ($item['title'] ?? 'BNS Message');
    $sub = $d['tagline'] ?? $d['headline'] ?? $d['intro'] ?? $d['hero_sub'] ?? '';
    if (($d['brand'] ?? '') !== '' && $headline === ($d['brand'] ?? '')) {
        $sub = $d['headline'] ?? $sub;
    }
    // Prefer `items` (web modal payloads) before legacy list keys.
    $list = $d['items'] ?? $d['experience'] ?? $d['why'] ?? $d['learn'] ?? $d['benefits'] ?? $d['highlights'] ?? $d['bring'] ?? $d['points'] ?? $d['reminders'] ?? [];
    if (! is_array($list)) {
        $list = [];
    }
    $listTitle = $d['list_title'] ?? $d['experience_title'] ?? $d['why_title'] ?? $d['learn_title'] ?? $d['benefits_title'] ?? $d['highlights_title'] ?? $d['bring_title'] ?? $d['reminder_title'] ?? 'Highlights';
    $intro = $d['intro'] ?? $d['what_intro'] ?? $d['thanks'] ?? $d['greeting'] ?? '';
    $focus = $d['what_focus'] ?? $d['focus'] ?? $d['reserved'] ?? '';
    $ctaUrl = $d['view_url'] ?? $d['register']['url'] ?? $d['portal_url'] ?? $d['about_url'] ?? $d['website'] ?? null;
    $ctaLabel = $d['view_label'] ?? $d['register']['label'] ?? $d['portal_label'] ?? 'Open Link';
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0 0 8px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($sub !== '' && $sub !== $headline)
                <p style="margin:0;font-size:14px;line-height:1.55;color:rgba(255,255,255,0.85);">{{ $sub }}</p>
            @endif
        </td>
    </tr>
</table>

@if($intro !== '' || $focus !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($intro !== '')
                <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#334155;">{{ $intro }}</p>
            @endif
            @if($focus !== '')
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid rgba(253,110,1,0.22);border-radius:12px;background:rgba(253,110,1,0.08);">
                    <tr>
                        <td style="padding:12px 14px;font-size:14px;line-height:1.65;font-weight:650;color:#0a1d37;">{{ $focus }}</td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($list !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $listTitle }}</span>
            </h3>
            @include('emails.layouts._checklist', ['items' => $list])
        </td>
    </tr>
</table>
@endif

@if($ctaUrl)
<p style="margin:18px 0 0;">
    <a href="{{ $ctaUrl }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#fd6e01;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">{{ $ctaLabel }}</a>
</p>
@endif
