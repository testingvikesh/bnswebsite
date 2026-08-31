<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('event_galleries')) {
            return;
        }

        Schema::table('event_galleries', function (Blueprint $table) {
            if (! Schema::hasColumn('event_galleries', 'picasa_url')) {
                $table->string('picasa_url', 1000)->nullable()->after('cover_path');
            }
            if (! Schema::hasColumn('event_galleries', 'picasa_label')) {
                $table->string('picasa_label')->nullable()->after('picasa_url');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('event_galleries')) {
            return;
        }

        Schema::table('event_galleries', function (Blueprint $table) {
            if (Schema::hasColumn('event_galleries', 'picasa_label')) {
                $table->dropColumn('picasa_label');
            }
            if (Schema::hasColumn('event_galleries', 'picasa_url')) {
                $table->dropColumn('picasa_url');
            }
        });
    }
};
