<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contact_inquiries') || Schema::hasColumn('contact_inquiries', 'admission_confirmed_at')) {
            return;
        }

        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->timestamp('admission_confirmed_at')->nullable()->index()->after('status');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('contact_inquiries') || ! Schema::hasColumn('contact_inquiries', 'admission_confirmed_at')) {
            return;
        }

        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->dropColumn('admission_confirmed_at');
        });
    }
};
