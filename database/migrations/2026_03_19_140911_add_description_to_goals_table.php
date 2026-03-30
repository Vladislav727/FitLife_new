<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (! Schema::hasColumn('goals', 'description')) {
            Schema::table('goals', function (Blueprint $table) {
                $table->string('description')->default('')->after('target_value');
            });
        }
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
