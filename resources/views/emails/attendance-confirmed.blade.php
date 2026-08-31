<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Confirmed | Business Navachar School</title>
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#0a1d37;">
@php
    $name = $attendance->full_name ?: 'Participant';
    $session = $attendance->sessionLabel();
    $eventTitle = $event['title'] ?? $session;
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f7;padding:28px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e2e8f0;">
                <tr>
                    <td style="background:linear-gradient(135deg,#0a2240 0%,#123a5e 100%);padding:32px 24px;text-align:center;">
                        <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#fbbf24;">Business Navachar School (BNS)</p>
                        <div style="width:64px;height:64px;margin:0 auto 14px;border-radius:50%;background:#16a34a;line-height:64px;font-size:28px;color:#ffffff;">✓</div>
                        <h1 style="margin:0 0 8px;font-size:26px;line-height:1.3;color:#ffffff;">Attendance Confirmed</h1>
                        <p style="margin:0;font-size:15px;line-height:1.6;color:#dbe4f0;">Thank you, {{ $name }}. Your attendance has been marked successfully.</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px 24px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
                            <tr>
                                <td style="padding:12px 16px;background:#f8fafc;border-bottom:1px solid #eef2f7;font-size:13px;font-weight:700;color:#64748b;">Session</td>
                                <td style="padding:12px 16px;border-bottom:1px solid #eef2f7;font-size:14px;font-weight:700;">{{ $eventTitle }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 16px;background:#f8fafc;border-bottom:1px solid #eef2f7;font-size:13px;font-weight:700;color:#64748b;">Reference No.</td>
                                <td style="padding:12px 16px;border-bottom:1px solid #eef2f7;font-size:14px;font-weight:700;">{{ $attendance->registration_number ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 16px;background:#f8fafc;border-bottom:1px solid #eef2f7;font-size:13px;font-weight:700;color:#64748b;">Program</td>
                                <td style="padding:12px 16px;border-bottom:1px solid #eef2f7;font-size:14px;font-weight:700;">{{ $attendance->program ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 16px;background:#f8fafc;border-bottom:1px solid #eef2f7;font-size:13px;font-weight:700;color:#64748b;">Mobile</td>
                                <td style="padding:12px 16px;border-bottom:1px solid #eef2f7;font-size:14px;font-weight:700;">{{ $attendance->mobile ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#64748b;">Attended At</td>
                                <td style="padding:12px 16px;font-size:14px;font-weight:700;">{{ $attendance->attended_at ? \Illuminate\Support\Carbon::parse($attendance->attended_at)->format('d M Y, h:i A') : '—' }} IST</td>
                            </tr>
                        </table>
                        <p style="margin:22px 0 0;font-size:13px;line-height:1.7;color:#64748b;">
                            For support: Helpline <strong style="color:#0a1d37;">+91 72086 28671</strong>
                            | WhatsApp <strong style="color:#0a1d37;">+91 70218 39703</strong>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
