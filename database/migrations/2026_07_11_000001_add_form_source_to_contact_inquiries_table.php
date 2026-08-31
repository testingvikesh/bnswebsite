<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_inquiries', 'form_source')) {
                $table->string('form_source', 50)->nullable()->after('registration_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('contact_inquiries', 'form_source')) {
                $table->dropColumn('form_source');
            }
        });
    }
};
