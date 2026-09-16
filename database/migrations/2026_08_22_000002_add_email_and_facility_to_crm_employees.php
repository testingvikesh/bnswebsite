<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_employees', function (Blueprint $table) {
            if (! Schema::hasColumn('crm_employees', 'email')) {
                $table->string('email')->nullable()->after('username');
            }
            if (! Schema::hasColumn('crm_employees', 'facility')) {
                $table->string('facility')->nullable()->after('mobile');
            }
        });
    }

    public function down(): void
    {
        Schema::table('crm_employees', function (Blueprint $table) {
            if (Schema::hasColumn('crm_employees', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('crm_employees', 'facility')) {
                $table->dropColumn('facility');
            }
        });
    }
};
