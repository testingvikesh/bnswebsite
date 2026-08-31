@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Quick Answers');
    $headline = (string) ($d['headline'] ?? 'Frequently Asked Questions (FAQ)');
    $items = is_array($d['items'] ?? null) ? $d['items'] : (is_array($d['faqs'] ?? null) ? $d['faqs'] : []);
    $assistTitle = (string) ($d['assist_title'] ?? 'Still have questions?');
    $botLabel = (string) ($d['bot_label'] ?? 'WhatsApp BOT');
    $botNumber = (string) ($d['bot_number'] ?? '');
    $botHint = (string) ($d['bot_hint'] ?? '');
    $botUrl = (string) ($d['bot_url'] ?? '');
@endphp

{{-- FAQ hero matching web .bns-faq-msg__hero --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 52%,#0f2744 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">❓</p>
            <h2 style="margin:0;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
        </td>
    </tr>
</table>

@if($items !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;">
    @foreach($items as $index => $row)
        @php
            $q = is_array($row) ? (string) ($row['q'] ?? $row['question'] ?? $row['title'] ?? '') : '';
            $a = is_array($row) ? (string) ($row['a'] ?? $row['answer'] ?? $row['text'] ?? '') : (string) $row;
            $num = str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT);
        @endphp
        @if($q !== '' || $a !== '')
            <tr>
                <td style="padding:0 0 8px;">
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
                        <tr>
                            <td style="padding:14px 16px;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="36" valign="top">
                                            <div style="width:28px;height:28px;border-radius:8px;background:#eef2ff;text-align:center;line-height:28px;font-size:11px;font-weight:800;color:#4f46e5;">{{ $num }}</div>
                                        </td>
                                        <td style="padding:2px 0 0 8px;font-size:14px;line-height:1.4;font-weight:800;color:#0a1d37;">{{ $q !== '' ? $q : $a }}</td>
                                    </tr>
                                </table>
                                @if($q !== '' && $a !== '')
                                    <p style="margin:10px 0 0 44px;font-size:13px;line-height:1.6;color:#475569;">{{ $a }}</p>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif
    @endforeach
</table>
@endif

@if($botUrl !== '' || $botNumber !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $assistTitle }}</h3>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #bbf7d0;border-radius:14px;background:#f0fdf4;">
                <tr>
                    <td style="padding:16px;">
                        <p style="margin:0 0 4px;font-size:12px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">{{ $botLabel }}</p>
                        @if($botNumber !== '')
                            <p style="margin:0 0 6px;font-size:20px;font-weight:800;color:#0a1d37;">{{ $botNumber }}</p>
                        @endif
                        @if($botHint !== '')
                            <p style="margin:0 0 12px;font-size:13px;line-height:1.5;color:#64748b;">{{ $botHint }}</p>
                        @endif
                        @if($botUrl !== '')
                            <a href="{{ $botUrl }}" style="display:inline-block;padding:11px 18px;border-radius:999px;background:#16a34a;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">Message BOT</a>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif
