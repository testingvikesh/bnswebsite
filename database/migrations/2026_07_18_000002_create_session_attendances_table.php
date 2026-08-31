<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_inquiry_id')->constrained('contact_inquiries')->cascadeOnDelete();
            $table->string('registration_number', 40)->nullable()->index();
            $table->unsignedTinyInteger('session_number')->default(1)->index();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('program')->nullable();
            $table->string('status', 30)->default('present')->index();
            $table->string('marked_via', 30)->default('self');
            $table->timestamp('attended_at')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['contact_inquiry_id', 'session_number'], 'session_attendances_inquiry_session_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_attendances');
    }
};
