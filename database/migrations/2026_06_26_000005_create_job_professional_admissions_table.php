<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_professional_admissions', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('category')->default('job_professional_growth');
            $table->string('full_name');
            $table->string('email');
            $table->string('mobile');
            $table->string('photo_path')->nullable();
            $table->json('form_data');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_professional_admissions');
    }
};
