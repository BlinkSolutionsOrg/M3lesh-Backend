<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Support circles (admin-managed): القلق، فراق، ضغط الشغل…
        Schema::create('circles', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('emoji')->nullable();       // emoji icon rendered as plain text
            $table->string('color')->nullable();       // hex e.g. "#5E82C9"
            $table->string('bg_color')->nullable();    // hex e.g. "#E8F0FB"
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('members_count')->default(0);   // denormalized
            $table->timestamp('last_activity_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();      // audit, not constrained
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
            $table->index(['is_active', 'created_at']);
        });

        // Circle membership (join/leave). members_count on circles is kept in sync.
        Schema::create('circle_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained('circles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['circle_id', 'user_id']);
        });

        // Wins board ("بورد المكاسب"): small daily wins members cheer on.
        Schema::create('circle_wins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained('circles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedBigInteger('cheers_count')->default(0);    // denormalized
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['circle_id', 'created_at']);
        });

        Schema::create('circle_win_cheers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_win_id')->constrained('circle_wins')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['circle_win_id', 'user_id']);
        });

        // Stories ("حكايات الدائرة"): longer shares with 💜 hearts.
        Schema::create('circle_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained('circles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedBigInteger('hearts_count')->default(0);    // denormalized
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['circle_id', 'created_at']);
        });

        Schema::create('circle_story_hearts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_story_id')->constrained('circle_stories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['circle_story_id', 'user_id']);
        });

        // Icebreaker cards ("كسر الجليد"): admin-authored, read-only for users.
        Schema::create('circle_icebreakers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->nullable()->constrained('circles')->nullOnDelete(); // null = global
            $table->string('tag_ar');
            $table->string('tag_en');
            $table->text('question_ar');
            $table->text('question_en');
            $table->string('color')->nullable();
            $table->string('bg_color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['circle_id', 'is_active', 'sort_order']);
        });

        // Weekly challenge ("تحدي الأسبوع") per circle.
        Schema::create('circle_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained('circles')->cascadeOnDelete();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('participants_count')->default(0); // denormalized
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['circle_id', 'is_active']);
        });

        // Challenge steps ("خطوات النهاردة"). Total = count of steps; progress = completed steps.
        Schema::create('circle_challenge_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_challenge_id')->constrained('circle_challenges')->cascadeOnDelete();
            $table->string('text_ar');
            $table->string('text_en');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['circle_challenge_id', 'sort_order']);
        });

        // Per-user step completion (the "done" checkmarks).
        // NOTE: explicit short FK/unique names — the auto-generated names exceed
        // MySQL's 64-char identifier limit for this long table+column combo.
        Schema::create('circle_challenge_step_completions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('circle_challenge_step_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->foreign('circle_challenge_step_id', 'ccsc_step_fk')
                ->references('id')->on('circle_challenge_steps')->cascadeOnDelete();
            $table->unique(['circle_challenge_step_id', 'user_id'], 'ccsc_step_user_unq');
        });

        // Member encouragement ("تشجيع بين الأعضاء").
        Schema::create('circle_challenge_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_challenge_id')->constrained('circle_challenges')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index(['circle_challenge_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circle_challenge_messages');
        Schema::dropIfExists('circle_challenge_step_completions');
        Schema::dropIfExists('circle_challenge_steps');
        Schema::dropIfExists('circle_challenges');
        Schema::dropIfExists('circle_icebreakers');
        Schema::dropIfExists('circle_story_hearts');
        Schema::dropIfExists('circle_stories');
        Schema::dropIfExists('circle_win_cheers');
        Schema::dropIfExists('circle_wins');
        Schema::dropIfExists('circle_members');
        Schema::dropIfExists('circles');
    }
};
