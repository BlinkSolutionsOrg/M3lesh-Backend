<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ——— Space content (private user entries) — models owned by the space-content agent ———

        // Journal entries ("يومياتي"): private dated notes.
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->string('mood')->nullable();
            $table->date('entry_date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'entry_date']);
        });

        // Gratitude notes ("علبة الامتنان"): small sticky notes.
        Schema::create('gratitude_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('text', 500);
            $table->string('color')->nullable();
            $table->decimal('rotation', 4, 1)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'created_at']);
        });

        // Future letters ("رسائل لبكرة"): letters that unlock at a future date.
        Schema::create('future_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('recipient_label');
            $table->text('body');
            $table->timestamp('unlock_at');
            $table->timestamp('opened_at')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('text_color')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'unlock_at']);
        });

        // ——— Catalogs + gamification (owned here) ———

        // Daily card tips ("سحبة النهاردة"): admin-authored gentle tips.
        Schema::create('daily_card_tips', function (Blueprint $table) {
            $table->id();
            $table->text('text_ar');
            $table->text('text_en');
            $table->string('emoji')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });

        // Per-user once-a-day draw record.
        Schema::create('daily_card_draws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('daily_card_tip_id')->nullable()->constrained('daily_card_tips')->nullOnDelete();
            $table->date('draw_date');
            $table->timestamps();

            $table->unique(['user_id', 'draw_date']);
        });

        // Achievements ("إنجازاتي"): admin-managed catalog of medals.
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('icon');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('criterion_ar');
            $table->string('criterion_en');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });

        // Per-user unlocked achievements.
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id')->constrained('achievements')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();

            $table->unique(['achievement_id', 'user_id']);
        });

        // Room decorations ("التزيينات"): unlocked by level.
        Schema::create('room_decorations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title_ar');
            $table->string('title_en');
            $table->unsignedInteger('required_level')->default(1);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_decorations');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('daily_card_draws');
        Schema::dropIfExists('daily_card_tips');
        Schema::dropIfExists('future_letters');
        Schema::dropIfExists('gratitude_notes');
        Schema::dropIfExists('journal_entries');
    }
};
