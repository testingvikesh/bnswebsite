<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_inquiries', 'auto_purge_at')) {
                $table->timestamp('auto_purge_at')->nullable()->index()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('contact_inquiries', 'auto_purge_at')) {
                $table->dropIndex(['auto_purge_at']);
                $table->dropColumn('auto_purge_at');
            }
        });
    }
};
