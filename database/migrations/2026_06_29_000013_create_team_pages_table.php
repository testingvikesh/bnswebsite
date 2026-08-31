<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->string('page_subtitle')->nullable();
            $table->text('page_intro');
            $table->string('leadership_title')->default('Leadership Team');
            $table->string('academic_title')->default('Academic Team');
            $table->string('advisory_title')->default('Advisory Board');
            $table->string('collab_badge')->nullable();
            $table->string('collab_title')->nullable();
            $table->text('collab_description')->nullable();
            $table->string('operations_title')->default('Operations Team');
            $table->json('operations_teams')->nullable();
            $table->string('values_title')->default('Our Team Values');
            $table->json('values_items')->nullable();
            $table->string('join_title')->default('Join Our Team');
            $table->text('join_intro')->nullable();
            $table->string('join_looking_label')->nullable();
            $table->json('join_roles')->nullable();
            $table->string('join_cta_title')->nullable();
            $table->text('join_cta_text')->nullable();
            $table->string('join_contact_email')->nullable();
            $table->string('hero_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_pages');
    }
};
