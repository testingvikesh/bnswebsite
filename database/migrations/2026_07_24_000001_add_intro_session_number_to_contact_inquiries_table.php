<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_inquiries', 'intro_session_number')) {
                $table->unsignedTinyInteger('intro_session_number')->nullable()->after('preferred_batch')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('contact_inquiries', 'intro_session_number')) {
                $table->dropColumn('intro_session_number');
            }
        });
    }
};
