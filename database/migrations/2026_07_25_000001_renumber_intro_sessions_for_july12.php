<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Remap stored session numbers after inserting Session 1 on 12 July 2026:
 * old Session 1 (08 Aug) → Session 2
 * old Session 2 (09 Aug) → Session 3
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->remap(2, 3);
        $this->remap(1, 2);
    }

    public function down(): void
    {
        $this->remap(2, 1);
        $this->remap(3, 2);
    }

    private function remap(int $from, int $to): void
    {
        if (Schema::hasTable('contact_inquiries') && Schema::hasColumn('contact_inquiries', 'intro_session_number')) {
            DB::table('contact_inquiries')
                ->where('intro_session_number', $from)
                ->update(['intro_session_number' => $to]);
        }

        if (Schema::hasTable('session_attendances') && Schema::hasColumn('session_attendances', 'session_number')) {
            DB::table('session_attendances')
                ->where('session_number', $from)
                ->update(['session_number' => $to]);
        }

        if (Schema::hasTable('intro_session_email_logs') && Schema::hasColumn('intro_session_email_logs', 'session_number')) {
            DB::table('intro_session_email_logs')
                ->where('session_number', $from)
                ->update(['session_number' => $to]);
        }
    }
};
