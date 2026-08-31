@php
    $d = is_array($data ?? null) ? $data : [];
    $nonMember = is_array($d['non_member'] ?? null) ? $d['non_member'] : [];
    $member = is_array($d['member'] ?? null) ? $d['member'] : [];
    $process = is_array($d['process'] ?? null) ? $d['process'] : [];
    $notes = is_array($d['notes'] ?? null) ? $d['notes'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#312e81 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#a78bfa,#7c3aed);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $d['eyebrow'] ?? 'Venue Partner Benefit' }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">🎓</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $d['headline'] ?? 'Scholarship Information' }}</h2>
            @if(!empty($d['intro']))
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.88);">{{ $d['intro'] }}</p>
            @endif
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 14px;font-size:15px;font-weight:800;color:#0a1d37;">📢 {{ $d['fee_title'] ?? 'Admission Fee' }}</h3>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" style="padding:0 6px 0 0;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:14px;background:#f8fafc;">
                            <tr>
                                <td style="padding:16px;">
                                    <p style="margin:0 0 10px;font-size:13px;font-weight:800;color:#0a1d37;">
                                        {{ ($nonMember['icon'] ?? '👤').' '.($nonMember['label'] ?? 'For Non-Members') }}
                                    </p>
                                    @if(!empty($nonMember['course_fee']))
                                        <p style="margin:0 0 10px;font-size:13px;line-height:1.45;color:#64748b;">{{ $nonMember['course_fee'] }}</p>
                                    @endif
                                    <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">
                                        {{ $nonMember['total_label'] ?? 'Total Payable' }}
                                    </p>
                                    <p style="margin:0;font-size:22px;font-weight:900;color:#0a1d37;">{{ $nonMember['total'] ?? '' }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" valign="top" style="padding:0 0 0 6px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #bbf7d0;border-radius:14px;background:#f0fdf4;">
                            <tr>
                                <td style="padding:16px;">
                                    <p style="margin:0 0 10px;font-size:13px;font-weight:800;color:#0a1d37;">
                                        {{ ($member['icon'] ?? '🌟').' '.($member['label'] ?? 'For Permanent Members') }}
                                    </p>
                                    @if(!empty($member['course_fee']))
                                        <p style="margin:0 0 10px;font-size:13px;line-height:1.45;color:#64748b;">{{ $member['course_fee'] }}</p>
                                    @endif
                                    <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#15803d;">
                                        {{ $member['total_label'] ?? 'Effective Fee' }}
                                    </p>
                                    <p style="margin:0 0 10px;font-size:22px;font-weight:900;color:#15803d;">{{ $member['total'] ?? '' }}</p>
                                    @if(!empty($member['benefit']))
                                        <p style="margin:0;padding:8px 10px;border-radius:10px;background:#dcfce7;font-size:12px;font-weight:700;color:#166534;">
                                            {{ $member['benefit_label'] ?? 'Special Benefit' }}: <strong>{{ $member['benefit'] }}</strong>
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@if($process !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📋 {{ $d['process_title'] ?? 'Admission Process' }}</h3>
            @foreach($process as $index => $step)
                @php $num = str_pad((string) ((int) $index + 1), 2, '0', STR_PAD_LEFT); @endphp
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #ede9fe;border-radius:12px;background:#faf5ff;">
                    <tr>
                        <td width="40" valign="top" style="padding:10px 0 10px 12px;">
                            <div style="width:28px;height:28px;border-radius:8px;background:#ede9fe;text-align:center;line-height:28px;font-size:11px;font-weight:800;color:#7c3aed;">{{ $num }}</div>
                        </td>
                        <td style="padding:10px 12px 10px 4px;font-size:14px;line-height:1.45;font-weight:700;color:#0a1d37;">{{ $step }}</td>
                    </tr>
                </table>
            @endforeach
        </td>
    </tr>
</table>
@endif

@if($notes !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">📌 {{ $d['notes_title'] ?? 'Important Instructions' }}</h3>
            @include('emails.layouts._checklist', ['items' => $notes])
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e0e7ff;border-radius:16px;background:#eef2ff;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🙋 {{ $d['assist_title'] ?? 'Need Any Assistance?' }}</h3>
            @if(!empty($d['assist']))
                <p style="margin:0 0 14px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['assist'] }}</p>
            @endif
            @if(!empty($d['pay_url']))
                <a href="{{ $d['pay_url'] }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#7c3aed;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">
                    {{ $d['pay_label'] ?? 'Pay Now' }}
                </a>
            @endif
        </td>
    </tr>
</table>

@if(!empty($d['thanks']) || !empty($d['family']) || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#312e81);">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['thanks']))
                <p style="margin:0 0 8px;font-size:15px;font-weight:800;color:#ffffff;">{{ $d['thanks'] }}</p>
            @endif
            @if(!empty($d['family']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.9);">{{ $d['family'] }}</p>
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
