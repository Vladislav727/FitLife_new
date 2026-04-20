<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'media_path')) {
                $table->string('media_path')->nullable()->after('content');
            }
        });

        if (Schema::hasColumn('posts', 'photo_path')) {
            DB::table('posts')
                ->whereNull('media_path')
                ->update(['media_path' => DB::raw('photo_path')]);
        }

        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'media_type')) {
                $table->string('media_type')->nullable()->after('media_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'media_type')) {
                $table->dropColumn('media_type');
            }
        });
    }
};
