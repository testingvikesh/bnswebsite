<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intro_session_email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_inquiry_id')->nullable()->constrained('contact_inquiries')->nullOnDelete();
            $table->unsignedTinyInteger('session_number')->index();
            $table->string('registration_number', 40)->nullable()->index();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('mobile', 30)->nullable();
            $table->string('status', 20)->default('sent')->index();
            $table->text('error_message')->nullable();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('batch_key', 40)->nullable()->index();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intro_session_email_logs');
    }
};
