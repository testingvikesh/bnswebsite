<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_images', function (Blueprint $table) {
            $table->id();
            $table->string('key', 80)->unique();
            $table->string('section', 100);
            $table->string('label');
            $table->string('default_path');
            $table->string('image_path')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_images');
    }
};
