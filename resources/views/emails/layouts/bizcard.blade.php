@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Networking Tip');
    $headline = (string) ($d['headline'] ?? 'Business Card Reminder');
    $greeting = (string) ($d['greeting'] ?? '');
    $intro = (string) ($d['intro'] ?? '');
    $whyTitle = (string) ($d['why_title'] ?? 'Why Bring Your Business Card?');
    $whyIntro = (string) ($d['why_intro'] ?? '');
    $why = is_array($d['why'] ?? null) ? $d['why'] : [];
    $altTitle = (string) ($d['alt_title'] ?? "If You Don't Have a Business Card");
    $altBadge = (string) ($d['alt_badge'] ?? '');
    $altWelcome = (string) ($d['alt_welcome'] ?? '');
    $altHint = (string) ($d['alt_hint'] ?? '');
    $fields = is_array($d['fields'] ?? null) ? $d['fields'] : [];
    $rememberTitle = (string) ($d['remember_title'] ?? 'Remember');
    $rememberQuote = (string) ($d['remember_quote'] ?? '');
    $rememberText = (string) ($d['remember_text'] ?? '');
    $closing = (string) ($d['closing'] ?? '');
    $brand = (string) ($d['brand'] ?? '');
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">💼</p>
            <h2 style="margin:0 0 10px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($intro !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $intro }}</p>
            @endif
        </td>
    </tr>
</table>

@if($whyIntro !== '' || $why !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🤝 {{ $whyTitle }}</h3>
            @if($whyIntro !== '')
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $whyIntro }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $why])
        </td>
    </tr>
</table>
@endif

@if($altBadge !== '' || $altWelcome !== '' || $altHint !== '' || $fields !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📌 {{ $altTitle }}</h3>
            @if($altBadge !== '')
                <p style="margin:0 0 10px;">
                    <span style="display:inline-block;padding:6px 12px;border-radius:999px;background:#fff7ed;border:1px solid #ffedd5;font-size:12px;font-weight:800;color:#fd6e01;">{{ $altBadge }}</span>
                </p>
            @endif
            @if($altWelcome !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $altWelcome }}</p>
            @endif
            @if($altHint !== '')
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $altHint }}</p>
            @endif
            @foreach($fields as $index => $field)
                @php
                    $text = is_array($field) ? (string) ($field['text'] ?? $field['label'] ?? '') : (string) $field;
                    $num = str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT);
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="40" valign="top" style="padding:10px 0 10px 12px;">
                                <div style="width:28px;height:28px;border-radius:8px;background:#eef2ff;text-align:center;line-height:28px;font-size:11px;font-weight:800;color:#4f46e5;">{{ $num }}</div>
                            </td>
                            <td style="padding:12px 12px 12px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($rememberQuote !== '' || $rememberText !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <p style="margin:0 0 8px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#fd6e01;">{{ $rememberTitle }}</p>
            @if($rememberQuote !== '')
                <p style="margin:0 0 10px;font-size:16px;line-height:1.45;font-weight:800;color:#0a1d37;">“{{ $rememberQuote }}”</p>
            @endif
            @if($rememberText !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:#475569;">{{ $rememberText }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($closing !== '' || $brand !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($closing !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#ffffff;">{{ $closing }}</p>
            @endif
            @if($brand !== '')
                <p style="margin:0 0 10px;font-size:14px;font-weight:800;color:#ffffff;">{{ $brand }}</p>
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
