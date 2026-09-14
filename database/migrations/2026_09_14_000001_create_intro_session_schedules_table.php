<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('intro_session_schedules')) {
            return;
        }

        Schema::create('intro_session_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('session_number')->unique();
            $table->string('title')->nullable();
            $table->string('date_label')->nullable();
            $table->string('time_label')->nullable();
            $table->dateTime('starts_at')->nullable()->index();
            $table->dateTime('ends_at')->nullable();
            $table->string('timezone', 64)->default('Asia/Kolkata');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intro_session_schedules');
    }
};
