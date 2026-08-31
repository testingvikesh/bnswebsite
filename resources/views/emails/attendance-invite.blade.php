<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance QR Invite | Business Navachar School</title>
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#0a1d37;">
@php
    $name = $invite->full_name ?: 'Participant';
    $sessionTitle = $event['title'] ?? ('Session '.$invite->session_number);
    $sessionDate = $event['date'] ?? '';
    $sessionTime = $event['time'] ?? '';
    $venue = $event['venue'] ?? ($event['location'] ?? '');
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f7;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e5e7eb;">
                <tr>
                    <td style="padding:28px 22px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#14532d 52%,#0d2944 100%);">
                        <p style="margin:0 0 10px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;">Attendance Invite</p>
                        <h1 style="margin:0 0 8px;font-size:24px;line-height:1.3;font-weight:800;color:#ffffff;">Scan QR to Mark Attendance</h1>
                        <p style="margin:0;font-size:14px;line-height:1.6;color:rgba(255,255,255,0.88);">Dear {{ $name }}, please use the QR code below on seminar day.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:22px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 14px;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
                            <tr>
                                <td style="padding:18px;">
                                    <h3 style="margin:0 0 14px;font-size:16px;font-weight:800;color:#0a1d37;">
                                        <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">📅 Session Details</span>
                                    </h3>
                                    @foreach([
                                        ['icon' => '🎫', 'label' => 'Session', 'value' => $sessionTitle],
                                        ['icon' => '📆', 'label' => 'Date', 'value' => $sessionDate],
                                        ['icon' => '🕖', 'label' => 'Time', 'value' => $sessionTime],
                                        ['icon' => '📍', 'label' => 'Venue', 'value' => $venue],
                                        ['icon' => '🆔', 'label' => 'Registration No.', 'value' => $invite->registration_number ?: '—'],
                                    ] as $row)
                                        @if(trim((string) $row['value']) !== '')
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                                            <tr>
                                                <td width="40" valign="middle" style="padding:12px 0 12px 12px;font-size:18px;">{{ $row['icon'] }}</td>
                                                <td style="padding:12px 12px 12px 4px;">
                                                    <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">{{ $row['label'] }}</p>
                                                    <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $row['value'] }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 14px;border:1px solid #bbf7d0;border-radius:16px;background:#f0fdf4;">
                            <tr>
                                <td style="padding:22px;text-align:center;">
                                    <h3 style="margin:0 0 8px;font-size:16px;font-weight:800;color:#14532d;">📱 Your Attendance QR Code</h3>
                                    <p style="margin:0 0 16px;font-size:13px;line-height:1.6;color:#166534;">Show this QR to a BNS volunteer. After volunteer verification &amp; approval, your attendance will be marked Present.</p>
                                    <img src="{{ $qrUrl }}" width="220" height="220" alt="Attendance QR Code" style="display:block;margin:0 auto 14px;border-radius:16px;border:6px solid #ffffff;box-shadow:0 10px 28px rgba(15,23,42,0.12);">
                                    <a href="{{ $scanUrl }}" style="display:inline-block;padding:12px 20px;border-radius:999px;background:linear-gradient(135deg,#22c55e,#15803d);color:#ffffff;text-decoration:none;font-size:14px;font-weight:800;">Open Attendance Link</a>
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
                            <tr>
                                <td style="padding:18px;">
                                    <h3 style="margin:0 0 12px;font-size:16px;font-weight:800;color:#0a1d37;">
                                        <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">✅ How it works</span>
                                    </h3>
                                    @foreach([
                                        'Keep this email ready on seminar day.',
                                        'Show the QR code to a BNS volunteer at the attendance counter.',
                                        'Volunteer will verify your details and approve the link.',
                                        'After approval you are marked Present and receive confirmation.',
                                    ] as $index => $step)
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                                            <tr>
                                                <td width="44" valign="middle" style="padding:12px 0 12px 12px;">
                                                    <span style="display:inline-block;min-width:28px;padding:6px 0;border-radius:8px;background:rgba(253,110,1,0.12);color:#c2410c;font-size:12px;font-weight:800;text-align:center;">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                                </td>
                                                <td style="padding:12px 12px 12px 4px;">
                                                    <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">Step</p>
                                                    <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $step }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    @endforeach
                                </td>
                            </tr>
                        </table>

                        <p style="margin:18px 0 0;font-size:13px;line-height:1.7;color:#64748b;text-align:center;">
                            Helpline: <strong style="color:#0a1d37;">+91 72086 28671</strong>
                            | WhatsApp: <strong style="color:#0a1d37;">+91 70218 39703</strong>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:14px 22px 20px;border-top:1px solid #eef2f7;background:#ffffff;">
                        <p style="margin:0;font-size:12px;line-height:1.6;color:#94a3b8;text-align:center;">Business Navachar School is owned and managed by BNS E-TECH PRIVATE LIMITED.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
