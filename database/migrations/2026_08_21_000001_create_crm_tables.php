<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username', 80)->unique();
            $table->string('password');
            $table->string('mobile', 30)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('crm_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_employee_id')->constrained('crm_employees')->cascadeOnDelete();
            $table->foreignId('contact_inquiry_id')->constrained('contact_inquiries')->cascadeOnDelete();
            $table->unsignedTinyInteger('session_number')->index();
            $table->string('attendance_status', 20)->default('present')->index();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->unique(['contact_inquiry_id', 'session_number'], 'crm_assignments_inquiry_session_unique');
            $table->index(['crm_employee_id', 'session_number']);
        });

        Schema::create('crm_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_assignment_id')->constrained('crm_assignments')->cascadeOnDelete();
            $table->unsignedTinyInteger('followup_no');
            $table->string('status', 40)->default('pending')->index();
            $table->text('note')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamps();

            $table->unique(['crm_assignment_id', 'followup_no'], 'crm_followups_assignment_no_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_followups');
        Schema::dropIfExists('crm_assignments');
        Schema::dropIfExists('crm_employees');
    }
};
