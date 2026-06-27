<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Community Q&A ("محتاج رأيكم"): asks people post to get the community's opinions.
        Schema::create('help_asks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('circle_id')->nullable()->constrained('circles')->nullOnDelete();
            $table->text('title');
            $table->text('body');
            $table->boolean('is_anonymous')->default(false);
            $table->string('status')->default('open');           // open | closed
            $table->unsignedBigInteger('replies_count')->default(0);  // denormalized
            $table->timestamp('last_activity_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();     // audit, not constrained
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['circle_id', 'created_at']);
        });

        // Replies on an ask (advice or experience) members upvote ("فادني").
        Schema::create('help_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('help_ask_id')->constrained('help_asks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');                               // advice | experience
            $table->text('body');
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedBigInteger('votes_count')->default(0);    // denormalized
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['help_ask_id', 'votes_count']);
        });

        Schema::create('help_reply_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('help_reply_id')->constrained('help_replies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['help_reply_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_reply_votes');
        Schema::dropIfExists('help_replies');
        Schema::dropIfExists('help_asks');
    }
};
