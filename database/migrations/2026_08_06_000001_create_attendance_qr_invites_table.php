<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_qr_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_inquiry_id')->constrained('contact_inquiries')->cascadeOnDelete();
            $table->unsignedTinyInteger('session_number')->index();
            $table->string('token', 64)->unique();
            $table->string('email')->nullable()->index();
            $table->string('full_name')->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('registration_number', 40)->nullable()->index();
            $table->string('status', 30)->default('pending')->index(); // pending|approved|revoked
            $table->timestamp('invite_sent_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_via', 30)->nullable(); // qr|admin
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('session_attendance_id')->nullable()->constrained('session_attendances')->nullOnDelete();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['contact_inquiry_id', 'session_number'], 'attendance_qr_invites_inquiry_session_unique');
        });

        Schema::table('session_attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('session_attendances', 'qr_token')) {
                $table->string('qr_token', 64)->nullable()->unique()->after('marked_via');
            }
        });
    }

    public function down(): void
    {
        Schema::table('session_attendances', function (Blueprint $table) {
            if (Schema::hasColumn('session_attendances', 'qr_token')) {
                $table->dropUnique(['qr_token']);
                $table->dropColumn('qr_token');
            }
        });

        Schema::dropIfExists('attendance_qr_invites');
    }
};
