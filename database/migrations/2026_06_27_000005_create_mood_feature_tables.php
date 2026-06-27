<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mood catalog (admin-authored): مبسوط، ماشي الحال، تعبان…
        Schema::create('moods', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label_ar');
            $table->string('label_en');
            $table->string('color')->nullable();       // hex e.g. "#5BB98B"
            $table->string('bg_color')->nullable();    // hex e.g. "#EAF6EF"
            $table->string('face_mood')->nullable();   // cat face: great|calm|sleepy|anxious|sad|happy
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });

        // Daily mood check-ins (one per user per day).
        Schema::create('mood_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mood_id')->nullable()->constrained('moods')->nullOnDelete();
            $table->string('mood_key');                // snapshot of the mood key
            $table->string('color')->nullable();       // snapshot of the mood color hex
            $table->text('note')->nullable();
            $table->date('checkin_date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'checkin_date']);
            $table->index(['user_id', 'checkin_date']);
        });

        // Per-user streak counters.
        Schema::create('mood_streaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('current_streak')->default(0);
            $table->unsignedInteger('longest_streak')->default(0);
            $table->date('last_checkin_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mood_streaks');
        Schema::dropIfExists('mood_checkins');
        Schema::dropIfExists('moods');
    }
};
