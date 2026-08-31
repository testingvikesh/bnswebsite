<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful | Business Navachar School</title>
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#0a1d37;">
@php
    $name = $payment->customer_name ?: 'Participant';
    $amount = number_format((float) $payment->amount, 2);
    $isIntroSession = ($payment->form_type ?? '') === 'intro-session';
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f7;padding:28px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 12px 32px rgba(15,23,42,0.08);">
                <tr>
                    <td style="background:linear-gradient(135deg,#0a2240 0%,#123a5e 100%);padding:32px 24px;text-align:center;">
                        <p style="margin:0 0 10px;font-size:12px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#fbbf24;">Business Navachar School (BNS)</p>
                        <div style="width:64px;height:64px;margin:0 auto 14px;border-radius:50%;background:#16a34a;line-height:64px;font-size:28px;color:#ffffff;">✓</div>
                        <h1 style="margin:0 0 8px;font-size:26px;line-height:1.3;color:#ffffff;">Payment Successful</h1>
                        <p style="margin:0;font-size:15px;line-height:1.6;color:#dbe4f0;">Thank you, {{ $name }}. Your payment has been received securely.</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#fff8f5;border-bottom:1px solid #ffe0d9;">
                            <tr>
                                <td style="padding:20px 24px;" align="left">
                                    <p style="margin:0;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#94a3b8;">Amount Paid</p>
                                </td>
                                <td style="padding:20px 24px;" align="right">
                                    <p style="margin:0;font-size:28px;font-weight:800;color:#ff5544;">₹ {{ $amount }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:28px 24px 8px;">
                        <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#475569;">
                            Your transaction is complete. Please keep this email as your payment confirmation. You can also open your online receipt anytime using the button below.
                        </p>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 22px;background:#fff5f3;border:1px solid #ffd4cc;border-radius:12px;">
                            <tr>
                                <td style="padding:16px 18px;">
                                    <p style="margin:0 0 6px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#ff5544;">Registration Number</p>
                                    <p style="margin:0;font-size:22px;font-weight:800;color:#0a1d37;">{{ $payment->registration_number ?: '—' }}</p>
                                </td>
                            </tr>
                        </table>

                        <h2 style="margin:0 0 14px;font-size:18px;color:#0a1d37;">Payment Details</h2>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
                            @foreach($details as $label => $value)
                                <tr>
                                    <td style="padding:12px 16px;width:42%;background:#f8fafc;border-bottom:1px solid #eef2f7;font-size:13px;font-weight:700;color:#64748b;vertical-align:top;">{{ $label }}</td>
                                    <td style="padding:12px 16px;border-bottom:1px solid #eef2f7;font-size:14px;font-weight:700;color:#0a1d37;vertical-align:top;">{{ $value }}</td>
                                </tr>
                            @endforeach
                        </table>

                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 12px;">
                            <tr>
                                <td style="border-radius:10px;background:#ff5544;">
                                    <a href="{{ $receiptUrl }}" target="_blank" rel="noopener noreferrer" style="display:inline-block;padding:14px 22px;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;">
                                        View / Download Receipt
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                @if($isIntroSession)
                <tr>
                    <td style="padding:8px 24px 24px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#0a2240;border-radius:14px;">
                            <tr>
                                <td style="padding:20px 18px;">
                                    <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#fbbf24;">Scholarship Notice</p>
                                    <p style="margin:0 0 10px;font-size:15px;font-weight:700;color:#ffffff;line-height:1.5;">Special Scholarship for Permanent Members of Santacruz Jain Upashray</p>
                                    <p style="margin:0;font-size:14px;line-height:1.7;color:#dbe4f0;">
                                        ₹{{ number_format($scholarshipAmount) }} Scholarship / Discount is available exclusively for Permanent Members.
                                        After payment, upload your Permanent Membership Proof. Once verified, ₹{{ number_format($scholarshipAmount) }} will be refunded.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                <tr>
                    <td style="padding:8px 24px 28px;">
                        <p style="margin:0 0 8px;font-size:13px;line-height:1.7;color:#64748b;">
                            For support: Helpline <strong style="color:#0a1d37;">+91 72086 28671</strong>
                            | WhatsApp <strong style="color:#0a1d37;">+91 70218 39703</strong>
                        </p>
                        <p style="margin:0;font-size:13px;line-height:1.7;color:#94a3b8;">
                            This is a computer-generated payment confirmation from Business Navachar School (BNS).
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
