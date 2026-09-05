<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund OTP | Business Navachar School</title>
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#0a1d37;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f7;padding:28px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e2e8f0;">
                <tr>
                    <td style="background:linear-gradient(135deg,#0a2240 0%,#123a5e 100%);padding:28px 24px;text-align:center;">
                        <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#fbbf24;">Business Navachar School (BNS)</p>
                        <h1 style="margin:0;font-size:24px;line-height:1.3;color:#ffffff;">Refund Verification OTP</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px 24px;">
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Use this OTP to approve the membership refund request:</p>
                        <p style="margin:0 0 20px;text-align:center;font-size:36px;font-weight:800;letter-spacing:0.28em;color:#0a2240;">{{ $otp }}</p>
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
                            <tr>
                                <td style="padding:16px 18px;font-size:14px;line-height:1.7;">
                                    <div><strong>Member:</strong> {{ $upload->membership_name }}</div>
                                    <div><strong>Membership No:</strong> {{ $upload->membership_no ?: '—' }}</div>
                                    <div><strong>Reg. No.:</strong> {{ $upload->registration_number ?: '—' }}</div>
                                    <div><strong>Mobile:</strong> {{ $upload->mobile ?: '—' }}</div>
                                    <div><strong>Refund Amount:</strong> ₹{{ number_format((float) $refundAmount, 2) }}</div>
                                </td>
                            </tr>
                        </table>
                        <p style="margin:18px 0 0;font-size:13px;line-height:1.6;color:#64748b;">This OTP expires in {{ $ttlMinutes }} minutes. If you did not request a refund, ignore this email.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
