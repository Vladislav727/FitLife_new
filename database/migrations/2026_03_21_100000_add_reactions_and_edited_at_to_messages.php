<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('reactable');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('emoji', 8);
            $table->timestamps();

            $table->unique(['reactable_type', 'reactable_id', 'user_id', 'emoji'], 'message_reactions_unique');
        });

        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->timestamp('edited_at')->nullable()->after('read_at');
        });

        Schema::table('group_messages', function (Blueprint $table) {
            $table->timestamp('edited_at')->nullable()->after('body');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_reactions');

        Schema::table('conversation_messages', function (Blueprint $table) {
            $table->dropColumn('edited_at');
        });

        Schema::table('group_messages', function (Blueprint $table) {
            $table->dropColumn('edited_at');
        });
    }
};
