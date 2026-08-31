<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $template['title'] ?? 'BNS Message' }}</title>
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#0a1d37;">
@php
    $name = trim((string) ($inquiry['full_name'] ?? ''));
    $registrationNumber = trim((string) ($inquiry['registration_number'] ?? ''));
    $stage = (string) ($template['stage'] ?? '');
    $title = (string) ($template['title'] ?? 'Session Message');
    $richHtml = trim((string) ($template['rich_html'] ?? ''));
    $bodyHtml = trim((string) ($template['body_html'] ?? ''));
    $hasRichUi = $richHtml !== '';
    $isMailPortal = ! empty($isMailPortal)
        || ($template['type'] ?? '') === 'mail_portal'
        || ($template['layout'] ?? '') === 'coach';
    $showPersonalGreeting = ! $hasRichUi && $name !== '' && strcasecmp($name, 'Participant') !== 0;
    $ctaLabel = $isMailPortal ? 'Visit BNS Website' : 'View All Session Messages';
    $ctaUrl = $messagesUrl ?: 'https://businessnavacharschool.com';
    $headerLabel = $isMailPortal ? 'Business Coach Mail' : $stage;
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f7;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e5e7eb;box-shadow:0 10px 30px rgba(15,23,42,0.06);">
                {{-- Stage strip only when rich body already has its own hero/title (avoids duplicate title) --}}
                <tr>
                    <td style="padding:{{ $hasRichUi ? '14px 22px' : '18px 22px' }};background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#312e81 100%);">
                        @if($headerLabel !== '')
                            <p style="margin:0{{ $hasRichUi ? '' : ' 0 6px' }};font-size:11px;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.78);">{{ $headerLabel }}</p>
                        @endif
                        @unless($hasRichUi)
                            <h1 style="margin:0;font-size:20px;line-height:1.35;font-weight:800;color:#ffffff;">{{ $title }}</h1>
                        @endunless
                    </td>
                </tr>
                <tr>
                    <td style="padding:{{ $hasRichUi ? '16px 14px 24px' : '18px 18px 28px' }};background:#f8fafc;">
                        @if($showPersonalGreeting)
                            <p style="margin:0 0 14px;padding:0 4px;font-size:15px;line-height:1.7;color:#0a1d37;">Dear {{ $name }},</p>
                        @endif

                        @if($registrationNumber !== '')
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 14px;background:#fff7ed;border:1px solid #ffedd5;border-radius:12px;">
                            <tr>
                                <td style="padding:12px 14px;">
                                    <p style="margin:0 0 4px;font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#fd6e01;">Reference Number</p>
                                    <p style="margin:0;font-size:17px;font-weight:800;color:#0a1d37;">{{ $registrationNumber }}</p>
                                </td>
                            </tr>
                        </table>
                        @endif

                        {{-- Same structured UI body as the web message modal --}}
                        @if($hasRichUi)
                            {!! $richHtml !!}
                        @else
                            {!! $bodyHtml !!}
                        @endif

                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:22px auto 0;">
                            <tr>
                                <td style="border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);">
                                    <a href="{{ $ctaUrl }}" style="display:inline-block;padding:12px 22px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ $ctaLabel }}</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 22px 22px;border-top:1px solid #eef2f7;background:#ffffff;">
                        <p style="margin:0;font-size:12px;line-height:1.6;color:#94a3b8;text-align:center;">Business Navachar School is owned and managed by BNS E-TECH PRIVATE LIMITED.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
