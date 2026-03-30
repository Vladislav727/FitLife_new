<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (! Schema::hasColumn('goal_logs', 'change')) {
            Schema::table('goal_logs', function (Blueprint $table) {
                $table->decimal('change', 10, 2)->default(0)->after('value');
            });
        }
    }

    public function down(): void
    {
        Schema::table('goal_logs', function (Blueprint $table) {
            $table->dropColumn('change');
        });
    }
};
