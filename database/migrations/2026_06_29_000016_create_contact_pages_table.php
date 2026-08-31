<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->string('page_subtitle')->nullable();
            $table->text('page_intro');
            $table->text('page_intro_2')->nullable();
            $table->string('office_title')->nullable();
            $table->string('office_brand')->nullable();
            $table->string('office_tagline')->nullable();
            $table->string('office_head_label')->nullable();
            $table->string('address_line')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pin_code', 20)->nullable();
            $table->string('phone_helpline', 30)->nullable();
            $table->string('phone_whatsapp', 30)->nullable();
            $table->string('phone_office', 30)->nullable();
            $table->string('email_admissions')->nullable();
            $table->string('email_general')->nullable();
            $table->string('email_media')->nullable();
            $table->string('website')->nullable();
            $table->json('office_hours')->nullable();
            $table->string('admission_support_title')->nullable();
            $table->text('admission_support_intro')->nullable();
            $table->json('admission_support_items')->nullable();
            $table->string('partnership_title')->nullable();
            $table->text('partnership_intro')->nullable();
            $table->json('partnership_items')->nullable();
            $table->string('faculty_cta_title')->nullable();
            $table->text('faculty_cta_text')->nullable();
            $table->string('faculty_cta_url')->nullable();
            $table->string('media_title')->nullable();
            $table->text('media_text')->nullable();
            $table->json('social_links')->nullable();
            $table->text('maps_embed_url')->nullable();
            $table->json('form_categories')->nullable();
            $table->string('immediate_title')->nullable();
            $table->string('immediate_call', 30)->nullable();
            $table->string('immediate_whatsapp', 30)->nullable();
            $table->string('immediate_intro_url')->nullable();
            $table->string('immediate_apply_url')->nullable();
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
        Schema::dropIfExists('contact_pages');
    }
};
