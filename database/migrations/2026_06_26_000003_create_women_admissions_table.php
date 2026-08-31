<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('women_admissions', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('category')->default('women_entrepreneurship_school');
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
        Schema::dropIfExists('women_admissions');
    }
};
