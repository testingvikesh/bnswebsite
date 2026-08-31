<?php

namespace App\Support;

use Carbon\Carbon;

class IcsCalendarBuilder
{
    /**
     * @param  array<string, mixed>  $event
     * @param  array<int, array<string, string>>  $reminders
     */
    public function build(
        array $event,
        string $attendeeEmail,
        string $attendeeName,
        string $uid,
        array $reminders = [],
        ?string $organizerEmail = null,
        ?string $organizerName = null,
    ): string {
        $timezone = (string) ($event['timezone'] ?? config('app.timezone', 'Asia/Kolkata'));
        $start = Carbon::parse((string) $event['starts_at'], $timezone);
        $end = Carbon::parse((string) ($event['ends_at'] ?? $event['starts_at']), $timezone);

        $summary = (string) ($event['title'] ?? 'Introduction Session').' — Business Navachar School';
        $location = (string) ($event['location_full'] ?? $event['venue'] ?? 'Santacruz, Mumbai');
        $description = $this->buildDescription($event, $attendeeName);
        $organizerEmail = $organizerEmail ?: (string) config('intro_session_email.organizer_email', 'info@businessnavacharschool.com');
        $organizerName = $organizerName ?: (string) config('intro_session_email.organizer_name', 'Business Navachar School');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Business Navachar School//Introduction Session//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:REQUEST',
            'BEGIN:VEVENT',
            'UID:'.$this->escapeText($uid),
            'DTSTAMP:'.$this->formatUtc(Carbon::now('UTC')),
            'DTSTART;TZID='.$timezone.':'.$start->format('Ymd\THis'),
            'DTEND;TZID='.$timezone.':'.$end->format('Ymd\THis'),
            'SUMMARY:'.$this->escapeText($summary),
            'DESCRIPTION:'.$this->escapeText($description),
            'LOCATION:'.$this->escapeText($location),
            'ORGANIZER;CN='.$this->escapeText($organizerName).':mailto:'.$organizerEmail,
            'ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN='.$this->escapeText($attendeeName).':mailto:'.$attendeeEmail,
            'STATUS:CONFIRMED',
            'SEQUENCE:0',
        ];

        foreach ($reminders as $reminder) {
            $lines[] = 'BEGIN:VALARM';
            $lines[] = 'TRIGGER:'.($reminder['trigger'] ?? '-PT1H');
            $lines[] = 'ACTION:DISPLAY';
            $lines[] = 'DESCRIPTION:'.$this->escapeText($reminder['description'] ?? 'Introduction Session reminder');
            $lines[] = 'END:VALARM';
        }

        $lines[] = 'END:VEVENT';
        $lines[] = 'END:VCALENDAR';

        return $this->foldLines($lines);
    }

    /** @param  array<string, mixed>  $event */
    public function googleCalendarUrl(array $event): string
    {
        $timezone = (string) ($event['timezone'] ?? config('app.timezone', 'Asia/Kolkata'));
        $start = Carbon::parse((string) $event['starts_at'], $timezone);
        $end = Carbon::parse((string) ($event['ends_at'] ?? $event['starts_at']), $timezone);

        $params = [
            'action' => 'TEMPLATE',
            'text' => ($event['title'] ?? 'Introduction Session 1').' — Business Navachar School',
            'dates' => $start->format('Ymd\THis').'/'.$end->format('Ymd\THis'),
            'ctz' => $timezone,
            'details' => strip_tags($this->buildDescription($event, '')),
            'location' => (string) ($event['location_full'] ?? $event['venue'] ?? 'Santacruz, Mumbai'),
        ];

        return 'https://calendar.google.com/calendar/render?'.http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }

    /** @param  array<string, mixed>  $event */
    private function buildDescription(array $event, string $attendeeName): string
    {
        $lines = array_filter([
            $attendeeName !== '' ? 'Registered by: '.$attendeeName : null,
            'Event: '.($event['title'] ?? 'Introduction Session 1'),
            'Date: '.($event['date'] ?? ''),
            'Time: '.($event['time'] ?? ''),
            'Venue: '.($event['venue'] ?? ''),
            'Who can join: '.($event['audience'] ?? ''),
            'BNS Team: '.($event['guest_faculty'] ?? ''),
            ! empty($event['benefits']) ? 'Benefits: '.implode(', ', (array) $event['benefits']) : null,
            ($event['seats'] ?? '') !== '' ? $event['seats'] : null,
            'Business Navachar School — https://businessnavacharschool.com',
        ]);

        return implode("\n", $lines);
    }

    private function formatUtc(Carbon $date): string
    {
        return $date->utc()->format('Ymd\THis\Z');
    }

    private function escapeText(string $value): string
    {
        return str_replace(
            ["\\", "\r\n", "\n", "\r", ',', ';'],
            ['\\\\', '\\n', '\\n', '\\n', '\\,', '\\;'],
            $value
        );
    }

    /** @param  array<int, string>  $lines */
    private function foldLines(array $lines): string
    {
        $folded = [];

        foreach ($lines as $line) {
            $line = str_replace(["\r", "\n"], '', $line);

            while (strlen($line) > 75) {
                $folded[] = substr($line, 0, 75);
                $line = ' '.substr($line, 75);
            }

            $folded[] = $line;
        }

        return implode("\r\n", $folded)."\r\n";
    }
}
