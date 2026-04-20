<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('likes')) {
            Schema::table('likes', function (Blueprint $table) {
                if (! Schema::hasColumn('likes', 'is_like')) {
                    $table->boolean('is_like')->default(true)->after('type');
                }
            });

            DB::statement('ALTER TABLE likes MODIFY COLUMN type VARCHAR(255) NOT NULL');
            DB::statement("UPDATE likes SET is_like = CASE WHEN type = 'dislike' THEN 0 ELSE 1 END");
            DB::statement("UPDATE likes SET type = 'post' WHERE type IN ('like', 'dislike')");

            if (! $this->indexExists('likes', 'likes_user_post_type_unique')) {
                Schema::table('likes', function (Blueprint $table) {
                    $table->unique(['user_id', 'post_id', 'type'], 'likes_user_post_type_unique');
                });
            }

            return;
        }

        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['post', 'comment']);
            $table->timestamps();
            $table->boolean('is_like')->default(true);
            $table->unique(['user_id', 'post_id', 'type']);
        });
    }

    public function down()
    {
        if (Schema::hasTable('likes') && $this->indexExists('likes', 'likes_user_post_type_unique')) {
            Schema::table('likes', function (Blueprint $table) {
                $table->dropUnique('likes_user_post_type_unique');
            });
        }

        Schema::dropIfExists('likes');
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
}
