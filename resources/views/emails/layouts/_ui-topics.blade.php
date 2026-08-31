{{-- Icon topic / feature rows (matches journey & similar web cards) --}}
@php
    $topics = is_array($topics ?? null) ? $topics : [];
    $topicsTitle = (string) ($topicsTitle ?? 'What Will You Learn?');
@endphp
@if($topics !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($topicsTitle !== '')
                <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $topicsTitle }}</span>
                </h3>
            @endif
            @foreach($topics as $topic)
                @php
                    $icon = is_array($topic) ? (string) ($topic['icon'] ?? '✨') : '✨';
                    $text = is_array($topic) ? (string) ($topic['text'] ?? $topic['label'] ?? '') : (string) $topic;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#ffffff;box-shadow:0 2px 8px rgba(15,23,42,0.04);">
                        <tr>
                            <td width="40" valign="middle" style="padding:12px 0 12px 14px;font-size:18px;">{{ $icon }}</td>
                            <td style="padding:12px 14px 12px 4px;font-size:14px;line-height:1.45;font-weight:800;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif
