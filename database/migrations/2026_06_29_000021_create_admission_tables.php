<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('page_title');
            $table->string('page_subtitle')->nullable();
            $table->text('page_intro')->nullable();
            $table->json('content_items')->nullable();
            $table->string('download_url')->nullable();
            $table->string('hero_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->string('category')->nullable();
            $table->string('program')->nullable();
            $table->string('year_level')->nullable();
            $table->string('batch')->nullable();
            $table->string('city')->nullable();
            $table->string('centre')->nullable();
            $table->string('full_name');
            $table->string('mobile', 30);
            $table->string('whatsapp', 30)->nullable();
            $table->string('email');
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('pin_code', 20)->nullable();
            $table->json('parent_details')->nullable();
            $table->string('education_qualification')->nullable();
            $table->string('institution_name')->nullable();
            $table->string('occupation')->nullable();
            $table->string('experience')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('photo_path')->nullable();
            $table->json('documents')->nullable();
            $table->json('fee_breakdown')->nullable();
            $table->string('payment_status', 30)->default('pending');
            $table->string('status', 30)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_applications');
        Schema::dropIfExists('admission_pages');
    }
};
