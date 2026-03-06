<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'email') && !Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('email', 'username');
            });
        }

        if (Schema::hasColumn('users', 'username')) {
            DB::statement('ALTER TABLE `users` MODIFY `username` VARCHAR(255) NOT NULL');
            try {
                DB::statement('ALTER TABLE `users` ADD UNIQUE `users_username_unique` (`username`)');
            } catch (\Throwable $e) {
                // Ignore when unique index already exists with another name.
            }
        }

        if (Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            try {
                DB::statement('ALTER TABLE `users` DROP INDEX `users_username_unique`');
            } catch (\Throwable $e) {
                // Ignore when index does not exist in this environment.
            }
        }

        if (Schema::hasColumn('users', 'username') && !Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('username', 'email');
            });
        }

        if (!Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            });
        }
    }
};
