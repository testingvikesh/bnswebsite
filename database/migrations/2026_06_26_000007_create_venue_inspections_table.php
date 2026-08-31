<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venue_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_number')->unique();
            $table->string('venue_name');
            $table->string('institution_name')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('mobile');
            $table->date('inspection_date');
            $table->string('inspector_name');
            $table->string('final_decision')->nullable();
            $table->json('form_data');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_inspections');
    }
};
