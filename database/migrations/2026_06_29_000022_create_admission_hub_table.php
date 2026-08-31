<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_hub', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->string('page_subtitle')->nullable();
            $table->text('page_intro');
            $table->text('page_intro_2')->nullable();
            $table->json('menu_items')->nullable();
            $table->string('trust_title')->nullable();
            $table->json('trust_items')->nullable();
            $table->string('after_admission_title')->nullable();
            $table->json('after_admission_items')->nullable();
            $table->string('dashboard_title')->nullable();
            $table->json('dashboard_items')->nullable();
            $table->string('office_counselor')->nullable();
            $table->string('office_phone', 30)->nullable();
            $table->string('office_whatsapp', 30)->nullable();
            $table->string('office_email')->nullable();
            $table->string('office_address')->nullable();
            $table->text('maps_embed_url')->nullable();
            $table->string('tagline_brand')->nullable();
            $table->string('tagline_text')->nullable();
            $table->string('tagline_subtext')->nullable();
            $table->string('tagline_hindi')->nullable();
            $table->string('hero_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_hub');
    }
};
