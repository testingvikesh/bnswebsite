<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->date('event_date')->nullable();
            $table->string('cover_path')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('event_gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_gallery_id')->constrained('event_galleries')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('caption')->nullable();
            $table->string('photo_path');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('event_gallery_reels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_gallery_id')->constrained('event_galleries')->cascadeOnDelete();
            $table->string('title');
            $table->text('caption')->nullable();
            $table->string('youtube_url');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_gallery_reels');
        Schema::dropIfExists('event_gallery_photos');
        Schema::dropIfExists('event_galleries');
    }
};
