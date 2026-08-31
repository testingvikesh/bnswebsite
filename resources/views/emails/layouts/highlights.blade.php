@php
    $d = is_array($data ?? null) ? $data : [];
    $experience = is_array($d['experience'] ?? null) ? $d['experience'] : [];
    // Fallback list keys used by older payloads
    if ($experience === []) {
        $experience = $d['highlights'] ?? $d['points'] ?? $d['learn'] ?? [];
    }
    $next = is_array($d['next'] ?? null) ? $d['next'] : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Past Seminar');
    $headline = (string) ($d['headline'] ?? 'Previous Seminar Highlights');
    $intro = (string) ($d['intro'] ?? '');
    $experienceTitle = (string) ($d['experience_title'] ?? 'During the seminar, participants experienced:');
@endphp

{{-- Purple hero matching web .bns-highlights__hero --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#312e81 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#a78bfa,#7c3aed);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0 0 10px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">📸 {{ $headline }}</h2>
            @if($intro !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $intro }}</p>
            @endif
        </td>
    </tr>
</table>

@if($experience !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(124,58,237,0.45);">{{ $experienceTitle }}</span>
            </h3>
            @foreach($experience as $point)
                @php
                    $text = is_array($point) ? (string) ($point['text'] ?? $point['label'] ?? '') : (string) $point;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #ede9fe;border-radius:12px;background:#faf5ff;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;">
                                <div style="width:22px;height:22px;border-radius:999px;background:#ede9fe;text-align:center;line-height:22px;color:#7c3aed;font-size:12px;font-weight:800;">✓</div>
                            </td>
                            <td style="padding:10px 12px 10px 8px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
            @if(!empty($d['feedback']))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:12px 0 0;border:1px solid #ede9fe;border-radius:12px;background:#f5f3ff;">
                    <tr>
                        <td style="padding:14px;font-size:14px;line-height:1.65;font-style:italic;color:#4c1d95;">“{{ $d['feedback'] }}”</td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($next !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">
                <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">🚀 {{ $d['next_title'] ?? "What's Next?" }}</span>
            </h3>
            @if(!empty($d['next_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['next_intro'] }}</p>
            @endif
            @foreach($next as $row)
                @php
                    $icon = is_array($row) ? (string) ($row['icon'] ?? '✨') : '✨';
                    $text = is_array($row) ? (string) ($row['text'] ?? '') : (string) $row;
                @endphp
                @if($text !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="36" valign="top" style="padding:10px 0 10px 12px;font-size:16px;">{{ $icon }}</td>
                            <td style="padding:10px 12px 10px 4px;font-size:14px;font-weight:700;color:#0a1d37;">{{ $text }}</td>
                        </tr>
                    </table>
                @endif
            @endforeach
        </td>
    </tr>
</table>
@endif

@if(!empty($d['opportunity']) || !empty($d['cta_text']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:14px;background:#fff7ed;">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if(!empty($d['opportunity']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;font-weight:700;color:#0a1d37;">{{ $d['opportunity'] }}</p>
            @endif
            @if(!empty($d['cta_text']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:#475569;">{{ $d['cta_text'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if(!empty($d['register_url']) || !empty($d['events_url']))
<p style="margin:16px 0 0;text-align:center;">
    @if(!empty($d['register_url']))
        <a href="{{ $d['register_url'] }}" style="display:inline-block;margin:0 6px 8px;padding:12px 18px;border-radius:999px;background:#7c3aed;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
            {{ $d['register_label'] ?? 'Register Now' }}
        </a>
    @endif
    @if(!empty($d['events_url']))
        <a href="{{ $d['events_url'] }}" style="display:inline-block;margin:0 6px 8px;padding:12px 18px;border-radius:999px;background:#0a1d37;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
            View Events
        </a>
    @endif
</p>
@endif
