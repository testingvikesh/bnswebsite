<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('mobile', 30);
            $table->string('whatsapp', 30)->nullable();
            $table->string('email');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('category')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->boolean('agreed_to_contact')->default(false);
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
