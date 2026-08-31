<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->string('page_subtitle')->nullable();
            $table->text('page_intro');
            $table->string('excellence_label')->default('Commitment');
            $table->string('excellence_title')->default('Faculty Excellence');
            $table->json('excellence_paragraphs')->nullable();
            $table->string('tagline_brand')->nullable();
            $table->string('tagline_text')->nullable();
            $table->string('hero_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_pages');
    }
};
