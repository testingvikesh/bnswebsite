<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $hasName = Schema::hasColumn('users', 'name');
        $hasFullName = Schema::hasColumn('users', 'full_name');

        if ($hasName && $hasFullName) {
            DB::table('users')
                ->where(function ($query) {
                    $query->whereNull('full_name')->orWhere('full_name', '');
                })
                ->whereNotNull('name')
                ->where('name', '!=', '')
                ->update(['full_name' => DB::raw('`name`')]);

            DB::table('users')
                ->where(function ($query) {
                    $query->whereNull('name')->orWhere('name', '');
                })
                ->whereNotNull('full_name')
                ->where('full_name', '!=', '')
                ->update(['name' => DB::raw('`full_name`')]);

            DB::statement("ALTER TABLE `users` MODIFY `full_name` VARCHAR(255) NOT NULL DEFAULT ''");
        } elseif ($hasFullName && ! $hasName) {
            DB::statement("ALTER TABLE `users` CHANGE `full_name` `name` VARCHAR(255) NOT NULL DEFAULT ''");
        }
    }

    public function down(): void
    {
        // Non-destructive sync migration — no rollback.
    }
};
