<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            $this->createUsersTable();

            return;
        }

        $this->renameLegacyPasswordColumn();
        $this->addColumnIfMissing('name', function (Blueprint $table) {
            $table->string('name')->default('')->after('id');
        });
        $this->addColumnIfMissing('email', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
        });
        $this->addColumnIfMissing('email_verified_at', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });
        $this->addColumnIfMissing('password', function (Blueprint $table) {
            $table->string('password')->default('')->after('email');
        });
        $this->addColumnIfMissing('role', function (Blueprint $table) {
            $table->string('role', 20)->default('user')->after('password');
        });
        $this->addColumnIfMissing('remember_token', function (Blueprint $table) {
            $table->rememberToken();
        });
        $this->addColumnIfMissing('created_at', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable();
        });
        $this->addColumnIfMissing('updated_at', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable();
        });

        $this->backfillEmptyNames();
    }

    public function down(): void
    {
        // Non-destructive sync migration — no rollback.
    }

    private function createUsersTable(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 20)->default('user');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    private function renameLegacyPasswordColumn(): void
    {
        if (Schema::hasColumn('users', 'password')) {
            return;
        }

        foreach (['user_password', 'pass', 'pwd', 'user_pass'] as $legacyColumn) {
            if (! Schema::hasColumn('users', $legacyColumn)) {
                continue;
            }

            $driver = Schema::getConnection()->getDriverName();

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE `users` CHANGE `{$legacyColumn}` `password` VARCHAR(255) NOT NULL DEFAULT ''");
            } else {
                Schema::table('users', function (Blueprint $table) use ($legacyColumn) {
                    $table->renameColumn($legacyColumn, 'password');
                });
            }

            return;
        }
    }

    private function addColumnIfMissing(string $column, callable $definition): void
    {
        if (Schema::hasColumn('users', $column)) {
            return;
        }

        Schema::table('users', $definition);
    }

    private function backfillEmptyNames(): void
    {
        if (! Schema::hasColumn('users', 'name') || ! Schema::hasColumn('users', 'email')) {
            return;
        }

        DB::table('users')
            ->where(function ($query) {
                $query->whereNull('name')->orWhere('name', '');
            })
            ->update([
                'name' => DB::raw("COALESCE(NULLIF(email, ''), CONCAT('User ', id))"),
            ]);
    }
};
