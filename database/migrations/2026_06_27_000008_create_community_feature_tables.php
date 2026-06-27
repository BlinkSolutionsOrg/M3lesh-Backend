<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Community seasons ("شجرة الدعم"): one active season at a time.
        // leaves_count is a denormalized live counter of support_leaves.
        Schema::create('community_seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->unsignedBigInteger('goal_leaves')->default(100000);
            $table->unsignedBigInteger('leaves_count')->default(0); // denormalized live counter
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
        });

        // Reward milestones on the shared-journey timeline.
        Schema::create('community_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_season_id')->constrained('community_seasons')->cascadeOnDelete();
            $table->unsignedBigInteger('threshold');
            $table->string('label_ar');
            $table->string('label_en');
            $table->string('reward_type')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['community_season_id', 'sort_order']);
        });

        // Support leaves: one row = one leaf = one person supporting another.
        Schema::create('support_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('community_season_id')->constrained('community_seasons')->cascadeOnDelete();
            $table->foreignId('circle_id')->nullable()->constrained('circles')->nullOnDelete();
            $table->string('action_type'); // support|advice|win|checkin|kind_word
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamps();

            $table->index(['community_season_id', 'created_at']);
            $table->index(['circle_id', 'community_season_id']);
            $table->index(['user_id', 'community_season_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_leaves');
        Schema::dropIfExists('community_milestones');
        Schema::dropIfExists('community_seasons');
    }
};
