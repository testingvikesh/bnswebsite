@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'From the Founder');
    $headline = (string) ($d['headline'] ?? "Founder's Welcome Message");
    $greeting = (string) ($d['greeting'] ?? '');
    $welcome = (string) ($d['welcome'] ?? '');
    $thanks = (string) ($d['thanks'] ?? '');
    $belief = (string) ($d['belief'] ?? '');
    $mission = (string) ($d['mission'] ?? '');
    $promise = is_array($d['promise'] ?? null) ? $d['promise'] : [];
    $grow = is_array($d['grow'] ?? null) ? $d['grow'] : [];
    $successLines = is_array($d['success_lines'] ?? null) ? $d['success_lines'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#14532d 52%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">💐</p>
            <h2 style="margin:0 0 12px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($greeting !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:#ffffff;">{{ $greeting }}</p>
            @endif
            @if($welcome !== '')
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.92);">{{ $welcome }}</p>
            @endif
            @if($thanks !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.84);">{{ $thanks }}</p>
            @endif
        </td>
    </tr>
</table>

@if($belief !== '' || $mission !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($belief !== '')
                <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#334155;">{{ $belief }}</p>
            @endif
            @if($mission !== '')
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #ffedd5;border-radius:12px;background:#fff7ed;">
                    <tr>
                        <td style="padding:14px;">
                            <p style="margin:0 0 6px;font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#fd6e01;">Mission</p>
                            <p style="margin:0;font-size:14px;line-height:1.65;color:#0a1d37;">{{ $mission }}</p>
                        </td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($promise !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🌟 {{ $d['promise_title'] ?? 'My Promise to You' }}</h3>
            @if(!empty($d['promise_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['promise_intro'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $promise])
            @if(!empty($d['promise_focus']))
                <p style="margin:10px 0 0;font-size:14px;line-height:1.65;font-weight:650;color:#0a1d37;">{{ $d['promise_focus'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($successLines !== [] || !empty($d['formula']))
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #dbeafe;border-radius:16px;background:#eff6ff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">🚀 {{ $d['success_title'] ?? 'Your Success is Our Mission' }}</h3>
            @foreach($successLines as $line)
                <p style="margin:0 0 6px;font-size:14px;line-height:1.55;font-weight:700;color:#1e3a5f;">{{ is_array($line) ? ($line['text'] ?? '') : $line }}</p>
            @endforeach
            @if(!empty($d['success_result']))
                <p style="margin:10px 0 0;font-size:14px;line-height:1.65;color:#334155;">{{ $d['success_result'] }}</p>
            @endif
            @if(!empty($d['formula_label']))
                <p style="margin:14px 0 4px;font-size:13px;font-style:italic;color:#64748b;">{{ $d['formula_label'] }}</p>
            @endif
            @if(!empty($d['formula']))
                <p style="margin:0;padding:12px 14px;border-radius:12px;background:#0a1d37;font-size:14px;font-weight:800;color:#ffffff;text-align:center;">{{ $d['formula'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

@if($grow !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 8px;font-size:15px;font-weight:800;color:#0a1d37;">🤝 {{ $d['grow_title'] ?? "Let's Grow Together" }}</h3>
            @if(!empty($d['grow_intro']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#475569;">{{ $d['grow_intro'] }}</p>
            @endif
            @include('emails.layouts._checklist', ['items' => $grow])
            @if(!empty($d['commitment']))
                <p style="margin:10px 0 0;font-size:14px;line-height:1.65;font-weight:700;color:#0a1d37;">{{ $d['commitment'] }}</p>
            @endif
        </td>
    </tr>
</table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;text-align:center;">
            @if(!empty($d['again_title']))
                <p style="margin:0 0 8px;font-size:13px;font-style:italic;font-weight:700;color:#fd6e01;">{{ $d['again_title'] }}</p>
            @endif
            @if(!empty($d['again_thanks']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['again_thanks'] }}</p>
            @endif
            @if(!empty($d['meet']))
                <p style="margin:0 0 8px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['meet'] }}</p>
            @endif
            @if(!empty($d['wish']))
                <p style="margin:0 0 12px;font-size:14px;line-height:1.65;color:#334155;">{{ $d['wish'] }}</p>
            @endif
            @if(!empty($d['regards']))
                <p style="margin:0 0 10px;font-size:13px;color:#64748b;">{{ $d['regards'] }}</p>
            @endif
            @if(!empty($d['name']))
                <p style="margin:0;font-size:16px;font-weight:800;color:#0a1d37;">{{ $d['name'] }}</p>
            @endif
            @if(!empty($d['role']))
                <p style="margin:4px 0 0;font-size:13px;color:#475569;">{{ $d['role'] }}</p>
            @endif
            @if(!empty($d['brand']))
                <p style="margin:2px 0 0;font-size:13px;font-weight:700;color:#0a1d37;">{{ $d['brand'] }}</p>
            @endif
        </td>
    </tr>
</table>

@if($motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @foreach($motto as $line)
                <p style="margin:0 0 6px;font-size:14px;font-weight:700;color:#ffffff;">
                    {{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}
                </p>
            @endforeach
        </td>
    </tr>
</table>
@endif
