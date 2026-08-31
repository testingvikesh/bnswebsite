<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('membership_name');
            $table->string('membership_no', 100);
            $table->string('photo_path');
            $table->string('email')->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('registration_number', 40)->nullable()->index();
            $table->string('status', 30)->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_uploads');
    }
};
