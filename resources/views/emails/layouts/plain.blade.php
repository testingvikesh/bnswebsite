@php
    /** @var string $title */
    /** @var string $eyebrow */
    /** @var string $bodyHtml */
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 16px;border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ strtoupper(str_replace(['_', '-'], ' ', $eyebrow ?: 'BNS Message')) }}
            </p>
            <h2 style="margin:0;font-size:22px;line-height:1.35;font-weight:800;color:#ffffff;">{{ $title }}</h2>
        </td>
    </tr>
</table>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            {!! $bodyHtml !!}
        </td>
    </tr>
</table>
