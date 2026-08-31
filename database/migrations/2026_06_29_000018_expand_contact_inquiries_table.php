<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->string('registration_number')->nullable()->unique()->after('id');
            $table->date('date_of_birth')->nullable()->after('email');
            $table->string('gender', 20)->nullable()->after('date_of_birth');
            $table->text('address')->nullable()->after('gender');
            $table->string('pin_code', 20)->nullable()->after('state');
            $table->string('country', 100)->nullable()->default('India')->after('pin_code');
            $table->string('interested_program')->nullable()->after('country');
            $table->string('educational_qualification')->nullable()->after('category');
            $table->string('occupation')->nullable()->after('educational_qualification');
            $table->string('organization_name')->nullable()->after('occupation');
            $table->string('preferred_centre')->nullable()->after('organization_name');
            $table->string('preferred_batch', 50)->nullable()->after('preferred_centre');
            $table->string('preferred_language', 50)->nullable()->after('preferred_batch');
            $table->string('hear_about')->nullable()->after('preferred_language');
            $table->json('purpose_of_joining')->nullable()->after('hear_about');
            $table->text('expectations')->nullable()->after('purpose_of_joining');
            $table->json('documents')->nullable()->after('message');
            $table->boolean('agreed_info_correct')->default(false)->after('agreed_to_contact');
            $table->boolean('agreed_privacy')->default(false)->after('agreed_info_correct');
        });
    }

    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->dropColumn([
                'registration_number', 'date_of_birth', 'gender', 'address',
                'pin_code', 'country', 'interested_program', 'educational_qualification',
                'occupation', 'organization_name', 'preferred_centre', 'preferred_batch',
                'preferred_language', 'hear_about', 'purpose_of_joining', 'expectations',
                'documents', 'agreed_info_correct', 'agreed_privacy',
            ]);
        });
    }
};
