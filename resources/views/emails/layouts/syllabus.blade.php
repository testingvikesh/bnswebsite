@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Curriculum');
    $headline = (string) ($d['headline'] ?? 'Course Syllabus');
    $question = (string) ($d['question'] ?? '');
    $intro = (string) ($d['intro'] ?? '');
    $highlights = is_array($d['highlights'] ?? null) ? $d['highlights'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];
    $viewUrl = (string) ($d['view_url'] ?? '');
    $viewLabel = (string) ($d['view_label'] ?? 'Open Course Syllabus');
    $viewTitle = (string) ($d['view_title'] ?? 'Course Syllabus');
    $punch = (string) ($d['punch'] ?? '');
    $brand = (string) ($d['brand'] ?? '');
@endphp

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 50%,#0d2944 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <p style="margin:0 0 10px;font-size:28px;line-height:1;">📚</p>
            <h2 style="margin:0 0 10px;font-size:22px;line-height:1.3;font-weight:800;color:#ffffff;">{{ $headline }}</h2>
            @if($question !== '')
                <p style="margin:0 0 8px;font-size:15px;font-weight:700;color:rgba(255,255,255,0.95);">{{ $question }}</p>
            @endif
            @if($intro !== '')
                <p style="margin:0;font-size:14px;line-height:1.65;color:rgba(255,255,255,0.85);">{{ $intro }}</p>
            @endif
        </td>
    </tr>
</table>

@if($highlights !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">🎯 {{ $d['highlights_title'] ?? 'Course Highlights' }}</h3>
            @include('emails.layouts._checklist', ['items' => $highlights])
        </td>
    </tr>
</table>
@endif

@if($viewUrl !== '')
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #ffedd5;border-radius:16px;background:#fff7ed;">
    <tr>
        <td style="padding:18px;text-align:center;">
            <p style="margin:0 0 10px;font-size:14px;font-weight:800;color:#0a1d37;">📘 {{ $viewTitle }}</p>
            <a href="{{ $viewUrl }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#fd6e01;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;">{{ $viewLabel }}</a>
        </td>
    </tr>
</table>
@endif

@if($punch !== '')
<p style="margin:14px 0 0;padding:14px 16px;border-radius:12px;background:#f8fafc;border:1px solid #e5e7eb;font-size:14px;line-height:1.65;color:#334155;">{{ $punch }}</p>
@endif

@if($brand !== '' || $motto !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border-radius:14px;background:linear-gradient(135deg,#0a1d37,#1e3a5f);">
    <tr>
        <td style="padding:16px;text-align:center;">
            @if($brand !== '')
                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#ffffff;">{{ $brand }}</p>
            @endif
            @foreach($motto as $line)
                <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#ffffff;">{{ is_array($line) ? trim(($line['icon'] ?? '').' '.($line['text'] ?? '')) : $line }}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif
