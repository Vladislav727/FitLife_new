<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToCommentsTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('comments') || Schema::hasColumn('comments', 'parent_id')) {
            return;
        }

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
        });
    }

    public function down()
    {
        if (! Schema::hasTable('comments') || ! Schema::hasColumn('comments', 'parent_id')) {
            return;
        }

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
