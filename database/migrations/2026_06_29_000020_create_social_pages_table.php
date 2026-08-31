<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->string('page_subtitle')->nullable();
            $table->text('page_intro');
            $table->text('page_intro_2')->nullable();
            $table->string('platforms_title')->nullable();
            $table->json('platforms')->nullable();
            $table->string('benefits_title')->nullable();
            $table->json('benefits_items')->nullable();
            $table->string('movement_title')->nullable();
            $table->text('movement_text')->nullable();
            $table->text('movement_text_2')->nullable();
            $table->string('quick_connect_title')->nullable();
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
        Schema::dropIfExists('social_pages');
    }
};
