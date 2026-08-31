@php
    $items = is_array($items ?? null) ? $items : [];
@endphp
@foreach($items as $point)
    @php
        if (is_array($point)) {
            $title = trim((string) ($point['title'] ?? $point['q'] ?? $point['question'] ?? ''));
            $text = trim((string) ($point['text'] ?? $point['label'] ?? $point['a'] ?? $point['answer'] ?? ''));
            if ($title !== '' && $text !== '' && $title !== $text) {
                $text = $title.' – '.$text;
            } elseif ($title !== '') {
                $text = $title;
            }
        } else {
            $text = trim((string) $point);
        }
        if ($text === '') {
            continue;
        }
    @endphp
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
        <tr>
            <td width="36" valign="top" style="padding:10px 0 10px 12px;">
                <div style="width:22px;height:22px;border-radius:999px;background:#dcfce7;text-align:center;line-height:22px;color:#16a34a;font-size:12px;font-weight:800;">✓</div>
            </td>
            <td style="padding:10px 12px 10px 8px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
        </tr>
    </table>
@endforeach
