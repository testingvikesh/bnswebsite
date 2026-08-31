{{-- Simple venue address block (journey / checklist style) --}}
@php
    $venue = is_array($venue ?? null) ? $venue : [];
    $title = (string) ($venue['title'] ?? 'Venue Address');
    $lines = is_array($venue['lines'] ?? null) ? $venue['lines'] : [];
    $mapsUrl = (string) ($venue['maps_url'] ?? '');
@endphp
@if($title !== '' || $lines !== [] || $mapsUrl !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffd4cc;border-radius:16px;background:#fff8f7;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(220,38,38,0.35);">📍 {{ $title !== '' ? $title : 'Venue Address' }}</span>
            </h3>
            @foreach($lines as $line)
                <p style="margin:0 0 4px;font-size:14px;line-height:1.55;color:#334155;">{{ $line }}</p>
            @endforeach
            @if($mapsUrl !== '')
                <p style="margin:14px 0 0;">
                    <a href="{{ $mapsUrl }}" style="display:inline-block;padding:10px 16px;border-radius:999px;background:rgba(37,99,235,0.1);color:#1d4ed8;font-size:13px;font-weight:800;text-decoration:none;">
                        📍 Open GPS Location
                    </a>
                </p>
            @endif
        </td>
    </tr>
</table>
@endif
