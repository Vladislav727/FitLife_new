<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (! Schema::hasColumn('notifications', 'sender_id')) {
                    $table->unsignedBigInteger('sender_id')->nullable()->after('user_id');
                }

                if (! Schema::hasColumn('notifications', 'notifiable_type')) {
                    $table->string('notifiable_type')->nullable()->after('type');
                }

                if (! Schema::hasColumn('notifications', 'notifiable_id')) {
                    $table->unsignedBigInteger('notifiable_id')->nullable()->after('notifiable_type');
                }

                if (! Schema::hasColumn('notifications', 'read_at')) {
                    $table->timestamp('read_at')->nullable()->after('notifiable_id');
                }
            });

            if (Schema::hasColumn('notifications', 'read')) {
                DB::statement('UPDATE notifications SET read_at = COALESCE(updated_at, created_at, NOW()) WHERE `read` = 1 AND read_at IS NULL');
            }

            DB::statement('UPDATE notifications SET sender_id = user_id WHERE sender_id IS NULL');

            if (Schema::hasColumn('notifications', 'related_id')) {
                DB::statement('UPDATE notifications SET notifiable_id = related_id WHERE notifiable_id IS NULL');
            }

            if (! $this->indexExists('notifications', 'notifications_user_id_read_at_index')) {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->index(['user_id', 'read_at']);
                });
            }

            return;
        }

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // like, comment, mention
            $table->nullableMorphs('notifiable'); // post or comment
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
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
};
