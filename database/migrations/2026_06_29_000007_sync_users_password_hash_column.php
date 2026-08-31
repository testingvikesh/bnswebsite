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

        $hasPassword = Schema::hasColumn('users', 'password');
        $hasPasswordHash = Schema::hasColumn('users', 'password_hash');

        if ($hasPassword && $hasPasswordHash) {
            DB::table('users')
                ->where(function ($query) {
                    $query->whereNull('password_hash')->orWhere('password_hash', '');
                })
                ->whereNotNull('password')
                ->where('password', '!=', '')
                ->update(['password_hash' => DB::raw('`password`')]);

            DB::table('users')
                ->where(function ($query) {
                    $query->whereNull('password')->orWhere('password', '');
                })
                ->whereNotNull('password_hash')
                ->where('password_hash', '!=', '')
                ->update(['password' => DB::raw('`password_hash`')]);

            DB::statement("ALTER TABLE `users` MODIFY `password_hash` VARCHAR(255) NOT NULL DEFAULT ''");
        } elseif ($hasPasswordHash && ! $hasPassword) {
            DB::statement("ALTER TABLE `users` CHANGE `password_hash` `password` VARCHAR(255) NOT NULL DEFAULT ''");
        }
    }

    public function down(): void
    {
        // Non-destructive sync migration — no rollback.
    }
};
