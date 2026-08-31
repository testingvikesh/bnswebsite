<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->string('page_subtitle')->nullable();
            $table->text('page_intro');
            $table->text('page_intro_2')->nullable();
            $table->string('help_title')->nullable();
            $table->text('help_intro')->nullable();
            $table->json('help_items')->nullable();
            $table->string('chat_title')->nullable();
            $table->string('whatsapp_number', 30)->nullable();
            $table->string('availability_label')->nullable();
            $table->json('availability_hours')->nullable();
            $table->json('quick_options')->nullable();
            $table->string('before_chat_title')->nullable();
            $table->text('before_chat_intro')->nullable();
            $table->json('before_chat_items')->nullable();
            $table->json('one_tap_actions')->nullable();
            $table->string('immediate_title')->nullable();
            $table->string('immediate_phone', 30)->nullable();
            $table->string('immediate_email')->nullable();
            $table->string('immediate_website')->nullable();
            $table->string('immediate_centre_url')->nullable();
            $table->string('brochure_url')->nullable();
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
        Schema::dropIfExists('whatsapp_pages');
    }
};
