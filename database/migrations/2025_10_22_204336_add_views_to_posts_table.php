<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewsToPostsTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('posts')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'views')) {
                $afterColumn = Schema::hasColumn('posts', 'media_type') ? 'media_type' : 'content';
                $table->unsignedBigInteger('views')->default(0)->after($afterColumn)->comment('Number of views for the post');
            }
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'views')) {
                $table->dropColumn('views');
            }
        });
    }
}
