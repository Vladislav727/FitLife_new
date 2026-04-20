<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (! Schema::hasTable('comments') || Schema::hasColumn('comments', 'reply_to_id')) {
            return;
        }

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('reply_to_id')->nullable()->after('parent_id')->constrained('comments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('comments') || ! Schema::hasColumn('comments', 'reply_to_id')) {
            return;
        }

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn('reply_to_id');
        });
    }
};
