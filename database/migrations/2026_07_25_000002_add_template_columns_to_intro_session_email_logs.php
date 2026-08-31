<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intro_session_email_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('intro_session_email_logs', 'template_key')) {
                $table->string('template_key', 120)->nullable()->after('session_number')->index();
            }
            if (! Schema::hasColumn('intro_session_email_logs', 'template_title')) {
                $table->string('template_title')->nullable()->after('template_key');
            }
        });
    }

    public function down(): void
    {
        Schema::table('intro_session_email_logs', function (Blueprint $table) {
            if (Schema::hasColumn('intro_session_email_logs', 'template_title')) {
                $table->dropColumn('template_title');
            }
            if (Schema::hasColumn('intro_session_email_logs', 'template_key')) {
                $table->dropColumn('template_key');
            }
        });
    }
};
