<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        if (Schema::hasTable('meal_logs') && ! $this->indexExists('meal_logs', 'meal_logs_user_date_index')) {
            Schema::table('meal_logs', function (Blueprint $table) {
                $table->index(['user_id', 'created_at'], 'meal_logs_user_date_index');
            });
        }

        if (Schema::hasTable('water_logs') && ! $this->indexExists('water_logs', 'water_logs_user_index')) {
            Schema::table('water_logs', function (Blueprint $table) {
                $table->index('user_id', 'water_logs_user_index');
            });
        }

        if (Schema::hasTable('sleeps') && ! $this->indexExists('sleeps', 'sleeps_user_date_index')) {
            Schema::table('sleeps', function (Blueprint $table) {
                $table->index(['user_id', 'date'], 'sleeps_user_date_index');
            });
        }

        if (Schema::hasTable('calendars') && ! $this->indexExists('calendars', 'calendars_user_date_index')) {
            Schema::table('calendars', function (Blueprint $table) {
                $table->index(['user_id', 'date'], 'calendars_user_date_index');
            });
        }

        if (Schema::hasTable('subscriptions') && ! $this->indexExists('subscriptions', 'subscriptions_unique_pair') && ! $this->indexExists('subscriptions', 'subscriptions_user_id_subscribed_user_id_unique')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->unique(['user_id', 'subscribed_user_id'], 'subscriptions_unique_pair');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('meal_logs') && $this->indexExists('meal_logs', 'meal_logs_user_date_index')) {
            Schema::table('meal_logs', function (Blueprint $table) {
                $table->dropIndex('meal_logs_user_date_index');
            });
        }

        if (Schema::hasTable('water_logs') && $this->indexExists('water_logs', 'water_logs_user_index')) {
            Schema::table('water_logs', function (Blueprint $table) {
                $table->dropIndex('water_logs_user_index');
            });
        }

        if (Schema::hasTable('sleeps') && $this->indexExists('sleeps', 'sleeps_user_date_index')) {
            Schema::table('sleeps', function (Blueprint $table) {
                $table->dropIndex('sleeps_user_date_index');
            });
        }

        if (Schema::hasTable('calendars') && $this->indexExists('calendars', 'calendars_user_date_index')) {
            Schema::table('calendars', function (Blueprint $table) {
                $table->dropIndex('calendars_user_date_index');
            });
        }

        if (Schema::hasTable('subscriptions') && $this->indexExists('subscriptions', 'subscriptions_unique_pair')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropUnique('subscriptions_unique_pair');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            return collect(DB::select("PRAGMA index_list('$table')"))
                ->contains(fn ($index) => ($index->name ?? null) === $indexName);
        }

        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }
};
