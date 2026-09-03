<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class IntroductionSessionTimeTest extends TestCase
{
    public function test_upcoming_sunday_sessions_are_two_thirty_to_four_thirty(): void
    {
        $config = require dirname(__DIR__, 2).'/config/events.php';
        $byNumber = [];

        foreach ($config['events'] as $event) {
            if (($event['type'] ?? '') !== 'introduction') {
                continue;
            }

            $number = (int) ($event['session_number'] ?? 0);
            if ($number > 0) {
                $byNumber[$number] = $event;
            }
        }

        foreach ([5, 6] as $number) {
            $this->assertArrayHasKey($number, $byNumber, 'Introduction Session '.$number.' must be configured.');
            $this->assertSame('2:30 PM – 4:30 PM', $byNumber[$number]['time']);
            $this->assertSame($number === 5 ? '2026-09-06 14:30:00' : '2026-09-20 14:30:00', $byNumber[$number]['starts_at']);
            $this->assertSame($number === 5 ? '2026-09-06 16:30:00' : '2026-09-20 16:30:00', $byNumber[$number]['ends_at']);
        }
    }
}
