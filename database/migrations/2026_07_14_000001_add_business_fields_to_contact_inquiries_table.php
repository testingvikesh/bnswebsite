<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->string('business_profession_category')->nullable()->after('organization_name');
            $table->string('business_category')->nullable()->after('business_profession_category');
            $table->text('products_services')->nullable()->after('business_category');
        });
    }

    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $table->dropColumn([
                'business_profession_category',
                'business_category',
                'products_services',
            ]);
        });
    }
};
