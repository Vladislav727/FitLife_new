<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (Schema::hasTable('post_views') && $this->needsRebuild()) {
            Schema::drop('post_views');
        }

        if (Schema::hasTable('post_views')) {
            return;
        }

        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at')->useCurrent();
            $table->unique(['post_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }

    private function needsRebuild(): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            return false;
        }

        $userIdType = DB::table('information_schema.columns')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'post_views')
            ->where('column_name', 'user_id')
            ->value('column_type');

        return ! Schema::hasColumn('post_views', 'viewed_at') || $userIdType !== 'bigint unsigned';
    }
};
