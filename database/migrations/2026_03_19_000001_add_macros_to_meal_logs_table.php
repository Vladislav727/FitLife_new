<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meal_logs', function (Blueprint $table) {
            $table->decimal('protein', 8, 2)->default(0)->after('calories');
            $table->decimal('fats', 8, 2)->default(0)->after('protein');
            $table->decimal('carbs', 8, 2)->default(0)->after('fats');
        });
    }

    public function down(): void
    {
        Schema::table('meal_logs', function (Blueprint $table) {
            $table->dropColumn(['protein', 'fats', 'carbs']);
        });
    }
};
