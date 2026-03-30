<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_seen_at')->nullable()->after('language');
        });

        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->foreignId('reply_to_id')->nullable()->after('user_id')
                ->constrained('conversation_messages')->nullOnDelete();
            $table->foreignId('forwarded_from_id')->nullable()->after('reply_to_id')
                ->constrained('conversation_messages')->nullOnDelete();
            $table->timestamp('pinned_at')->nullable()->after('edited_at');
            $table->string('file_path')->nullable()->after('media_type');
            $table->string('file_name')->nullable()->after('file_path');
            $table->unsignedBigInteger('file_size')->nullable()->after('file_name');
        });

        Schema::table('group_messages', function (Blueprint $table) {
            $table->foreignId('reply_to_id')->nullable()->after('user_id')
                ->constrained('group_messages')->nullOnDelete();
            $table->foreignId('forwarded_from_id')->nullable()->after('reply_to_id')
                ->constrained('group_messages')->nullOnDelete();
            $table->timestamp('pinned_at')->nullable()->after('edited_at');
            $table->string('file_path')->nullable()->after('media_type');
            $table->string('file_name')->nullable()->after('file_path');
            $table->unsignedBigInteger('file_size')->nullable()->after('file_name');
        });

        Schema::table('group_members', function (Blueprint $table) {
            $table->unsignedBigInteger('last_read_message_id')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropColumn('last_read_message_id');
        });

        Schema::table('group_messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropForeign(['forwarded_from_id']);
            $table->dropColumn(['reply_to_id', 'forwarded_from_id', 'pinned_at', 'file_path', 'file_name', 'file_size']);
        });

        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropForeign(['forwarded_from_id']);
            $table->dropColumn(['reply_to_id', 'forwarded_from_id', 'pinned_at', 'file_path', 'file_name', 'file_size']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_seen_at');
        });
    }
};
