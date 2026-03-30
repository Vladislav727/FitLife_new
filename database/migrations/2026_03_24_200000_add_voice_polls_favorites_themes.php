<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->string('audio_path')->nullable()->after('media_type');
            $table->unsignedInteger('audio_duration')->nullable()->after('audio_path');
        });

        Schema::table('group_messages', function (Blueprint $table) {
            $table->string('audio_path')->nullable()->after('media_type');
            $table->unsignedInteger('audio_duration')->nullable()->after('audio_path');
        });

        Schema::create('group_polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_message_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('question');
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_multiple')->default(false);
            $table->timestamp('closes_at')->nullable();
            $table->timestamps();
        });

        Schema::create('group_poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_poll_id')->constrained()->cascadeOnDelete();
            $table->string('text');
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('group_poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_poll_option_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['group_poll_id', 'group_poll_option_id', 'user_id'], 'poll_vote_unique');
        });

        Schema::create('message_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('message');
            $table->timestamps();

            $table->unique(['user_id', 'message_type', 'message_id'], 'fav_unique');
        });

        Schema::create('chat_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('chat');
            $table->string('theme_key');
            $table->timestamps();

            $table->unique(['user_id', 'chat_type', 'chat_id'], 'theme_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_themes');
        Schema::dropIfExists('message_favorites');
        Schema::dropIfExists('group_poll_votes');
        Schema::dropIfExists('group_poll_options');
        Schema::dropIfExists('group_polls');

        Schema::table('group_messages', function (Blueprint $table) {
            $table->dropColumn(['audio_path', 'audio_duration']);
        });

        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->dropColumn(['audio_path', 'audio_duration']);
        });
    }
};
