<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visiting_expert_faculty', function (Blueprint $table) {
            $table->id();
            $table->string('title_prefix')->nullable();
            $table->string('full_name');
            $table->string('photo_path')->nullable();
            $table->string('designation')->default('Visiting Expert Faculty');
            $table->string('recognition')->nullable();
            $table->text('expertise');
            $table->string('professional_experience')->nullable();
            $table->string('industry')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->unsignedSmallInteger('faculty_since')->nullable();
            $table->string('sessions_conducted')->nullable();
            $table->string('learners_mentored')->nullable();
            $table->string('languages')->nullable();
            $table->text('about')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visiting_expert_faculty');
    }
};
