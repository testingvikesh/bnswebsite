<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advisory_board_members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('designation');
            $table->string('organization')->nullable();
            $table->string('expertise');
            $table->text('profile');
            $table->string('photo_path')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisory_board_members');
    }
};
