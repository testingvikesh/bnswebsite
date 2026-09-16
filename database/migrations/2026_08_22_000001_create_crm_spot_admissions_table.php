<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_spot_admissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('mobile', 30)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('register_no', 40)->nullable()->index();
            $table->text('qr_data')->nullable();
            $table->string('facility_name')->nullable();
            $table->string('reason', 255)->nullable();
            $table->timestamp('attended_at')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_spot_admissions');
    }
};
