<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $copy['subject'] ?? 'Introduction Session 1' }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;color:#0a1d37;">
@php
    $name = $inquiry['full_name'] ?? 'Participant';
    $registrationNumber = $inquiry['registration_number'] ?? '';
    $greeting = str_replace(':name', $name, $copy['greeting'] ?? 'Dear :name,');
@endphp
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f6f9;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
                <tr>
                    <td style="background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#7f1d1d 100%);padding:28px 24px;">
                        <p style="margin:0 0 8px;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#ff8f82;">Business Navachar School</p>
                        <h1 style="margin:0;font-size:24px;line-height:1.3;color:#ffffff;">{{ $event['title'] ?? 'Introduction Session' }}</h1>
                        <p style="margin:10px 0 0;font-size:14px;line-height:1.6;color:#dbe4f0;">{{ $event['date'] ?? '' }} · {{ $event['time'] ?? '' }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px 24px;">
                        <p style="margin:0 0 16px;font-size:16px;line-height:1.7;color:#0a1d37;">{{ $greeting }}</p>
                        <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#475569;">{{ $copy['intro'] ?? '' }}</p>

                        @if($registrationNumber !== '')
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:#fff5f3;border:1px solid #ffd4cc;border-radius:12px;">
                            <tr>
                                <td style="padding:16px 18px;">
                                    <p style="margin:0 0 6px;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#ff5544;">{{ $copy['reference_label'] ?? 'Your Reference Number' }}</p>
                                    <p style="margin:0;font-size:22px;font-weight:800;color:#0a1d37;">{{ $registrationNumber }}</p>
                                </td>
                            </tr>
                        </table>
                        @endif

                        <h2 style="margin:0 0 16px;font-size:18px;color:#0a1d37;">{{ $copy['event_heading'] ?? 'Event Details' }}</h2>

                        @include('emails.partials.session-venue-card', [
                            'venueCard' => bns_intro_session_venue_card($event ?? []),
                            'event' => $event ?? [],
                        ])

                        @if(!empty($event['audience']) || !empty($event['guest_faculty']))
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
                            <tr>
                                <td style="padding:16px 18px;">
                                    @if(!empty($event['audience']))
                                        <p style="margin:0 0 10px;font-size:14px;line-height:1.6;color:#334155;"><strong>Who Can Join:</strong> {{ $event['audience'] }}</p>
                                    @endif
                                    @if(!empty($event['guest_faculty']))
                                        <p style="margin:0;font-size:14px;line-height:1.6;color:#334155;"><strong>BNS Team:</strong> {{ $event['guest_faculty'] }}</p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        @endif

                        @if(!empty($event['benefits']))
                        <h3 style="margin:0 0 12px;font-size:16px;color:#0a1d37;">Benefits</h3>
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;">
                            @foreach($event['benefits'] as $benefit)
                            <tr>
                                <td style="padding:0 0 8px;font-size:14px;line-height:1.6;color:#334155;">✓ {{ $benefit }}</td>
                            </tr>
                            @endforeach
                        </table>
                        @endif

                        @if(!empty($event['seats']))
                        <p style="margin:0 0 24px;display:inline-block;padding:8px 14px;border-radius:999px;background:#fff5f3;color:#ff5544;font-size:13px;font-weight:700;">{{ $event['seats'] }}</p>
                        @endif

                        <h2 style="margin:0 0 12px;font-size:18px;color:#0a1d37;">{{ $copy['calendar_heading'] ?? 'Add to Your Calendar' }}</h2>
                        <p style="margin:0 0 18px;font-size:14px;line-height:1.7;color:#475569;">{{ $copy['calendar_text'] ?? '' }}</p>

                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 24px;">
                            <tr>
                                <td style="border-radius:8px;background:#ff5544;">
                                    <a href="{{ $googleCalendarUrl }}" target="_blank" rel="noopener noreferrer" style="display:inline-block;padding:14px 22px;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;">
                                        {{ $copy['google_calendar_button'] ?? 'Add to Google Calendar' }}
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;">
                            <tr>
                                <td style="padding:20px 18px;">
                                    <h2 style="margin:0 0 10px;font-size:18px;color:#0a1d37;">{{ $copy['messages_heading'] ?? 'View All Session Messages' }}</h2>
                                    <p style="margin:0 0 18px;font-size:14px;line-height:1.7;color:#475569;">{{ $copy['messages_text'] ?? 'Open the full message hub to view all Introduction Session messages in one place.' }}</p>
                                    <table role="presentation" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td style="border-radius:8px;background:#0a1d37;">
                                                <a href="{{ $messagesUrl }}" target="_blank" rel="noopener noreferrer" style="display:inline-block;padding:14px 22px;font-size:15px;font-weight:700;color:#ffffff;text-decoration:none;">
                                                    {{ $copy['messages_button'] ?? 'View All Messages' }}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0;font-size:13px;line-height:1.6;color:#64748b;">{{ $copy['footer_note'] ?? '' }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
