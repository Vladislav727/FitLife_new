<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->string('media_path')->nullable()->after('body');
            $table->string('media_type')->nullable()->after('media_path');
        });

        Schema::table('group_messages', function (Blueprint $table) {
            $table->string('media_path')->nullable()->after('body');
            $table->string('media_type')->nullable()->after('media_path');
        });

        // Make body nullable for media-only messages
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->text('body')->nullable()->change();
        });

        Schema::table('group_messages', function (Blueprint $table) {
            $table->text('body')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->dropColumn(['media_path', 'media_type']);
            $table->text('body')->nullable(false)->change();
        });

        Schema::table('group_messages', function (Blueprint $table) {
            $table->dropColumn(['media_path', 'media_type']);
            $table->text('body')->nullable(false)->change();
        });
    }
};
