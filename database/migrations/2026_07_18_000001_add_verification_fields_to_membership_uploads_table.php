<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_uploads', function (Blueprint $table) {
            $table->string('trustee_status', 30)->default('pending')->after('status')->index();
            $table->text('trustee_remarks')->nullable()->after('trustee_status');
            $table->foreignId('trustee_verified_by')->nullable()->after('trustee_remarks')->constrained('users')->nullOnDelete();
            $table->timestamp('trustee_verified_at')->nullable()->after('trustee_verified_by');

            $table->string('bns_status', 30)->default('pending')->after('trustee_verified_at')->index();
            $table->text('bns_remarks')->nullable()->after('bns_status');
            $table->foreignId('bns_verified_by')->nullable()->after('bns_remarks')->constrained('users')->nullOnDelete();
            $table->timestamp('bns_verified_at')->nullable()->after('bns_verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('membership_uploads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('trustee_verified_by');
            $table->dropConstrainedForeignId('bns_verified_by');
            $table->dropColumn([
                'trustee_status',
                'trustee_remarks',
                'trustee_verified_at',
                'bns_status',
                'bns_remarks',
                'bns_verified_at',
            ]);
        });
    }
};
