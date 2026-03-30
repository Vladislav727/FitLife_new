<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::table('meal_logs', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'meal_logs_user_date_index');
        });

        Schema::table('water_logs', function (Blueprint $table) {
            $table->index('user_id', 'water_logs_user_index');
        });

        Schema::table('sleeps', function (Blueprint $table) {
            $table->index(['user_id', 'date'], 'sleeps_user_date_index');
        });

        Schema::table('calendars', function (Blueprint $table) {
            $table->index(['user_id', 'date'], 'calendars_user_date_index');
        });

        Schema::table('friends', function (Blueprint $table) {
            $table->unique(['user_id', 'friend_id'], 'friends_unique_pair');
        });
    }

    public function down(): void
    {
        Schema::table('meal_logs', function (Blueprint $table) {
            $table->dropIndex('meal_logs_user_date_index');
        });

        Schema::table('water_logs', function (Blueprint $table) {
            $table->dropIndex('water_logs_user_index');
        });

        Schema::table('sleeps', function (Blueprint $table) {
            $table->dropIndex('sleeps_user_date_index');
        });

        Schema::table('calendars', function (Blueprint $table) {
            $table->dropIndex('calendars_user_date_index');
        });

        Schema::table('friends', function (Blueprint $table) {
            $table->dropUnique('friends_unique_pair');
        });
    }
};
