<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {

        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 20)->nullable()->after('name');
            });
        }

        $users = User::whereNull('username')->orWhere('username', '')->get();
        foreach ($users as $index => $user) {
            $base = Str::slug($user->name, '') ?: 'user';
            $username = $base;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $base.$counter++;
            }
            $user->update(['username' => $username]);
        }

        $connection = Schema::getConnection()->getDriverName();

        if ($connection === 'mysql') {
            $indexes = DB::select('SHOW INDEX FROM users');
            $hasUnique = collect($indexes)->contains(function ($index) {
                return $index->Key_name === 'users_username_unique';
            });

            Schema::table('users', function (Blueprint $table) use ($hasUnique) {
                $table->string('username', 20)->nullable(false)->change();
                if (! $hasUnique) {
                    $table->unique('username');
                }
            });
        } elseif ($connection === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('users')");
            $hasUnique = collect($indexes)->contains(function ($index) {
                return str_contains($index->name, 'users_username_unique');
            });

            if (! $hasUnique) {
                DB::statement('CREATE UNIQUE INDEX users_username_unique ON users(username)');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                try {
                    $table->dropUnique(['username']);
                } catch (\Throwable $e) {

                }
                $table->dropColumn('username');
            });
        }
    }
};
