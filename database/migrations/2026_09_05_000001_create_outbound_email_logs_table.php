<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('outbound_email_logs')) {
            return;
        }

        Schema::create('outbound_email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('process', 50)->default('other')->index();
            $table->string('mailable', 191)->nullable();
            $table->string('to_email', 500);
            $table->string('cc_email', 500)->nullable();
            $table->string('from_email', 255)->nullable();
            $table->string('subject', 500)->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->string('status', 20)->default('sent')->index();
            $table->text('error_message')->nullable();
            $table->string('mailer', 50)->nullable();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sent_by_name', 255)->nullable();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbound_email_logs');
    }
};
