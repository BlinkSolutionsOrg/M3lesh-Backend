<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daily wheel challenge ("عجلة معلش"): admin-authored, one active at a time.
        // The 8 wedges stay a client const; segment_emoji maps to a wedge index.
        Schema::create('wheel_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('segment_emoji');             // e.g. "😂" — mapped to a wedge index client-side
            $table->string('pill_label_ar');
            $table->string('pill_label_en');
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('compose_hint_ar')->nullable();
            $table->text('compose_hint_en')->nullable();
            $table->text('room_banner_ar')->nullable();
            $table->text('room_banner_en')->nullable();
            $table->string('color')->nullable();         // hex e.g. "#3D6B52"
            $table->string('bg_color')->nullable();      // hex e.g. "#EAF6EF"
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('spins_count')->default(0);          // denormalized
            $table->unsignedBigInteger('responses_count')->default(0);      // denormalized
            $table->unsignedBigInteger('room_messages_count')->default(0);  // denormalized
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'created_at']);
        });

        // Per-user spin (idempotent via unique). spins_count is kept in sync.
        Schema::create('wheel_spins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wheel_challenge_id')->constrained('wheel_challenges')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['wheel_challenge_id', 'user_id']);
        });

        // Replies to today's wheel challenge ("ردود الناس النهاردة") with 😂/❤️ reactions.
        Schema::create('wheel_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wheel_challenge_id')->constrained('wheel_challenges')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedBigInteger('laugh_count')->default(0);   // denormalized
            $table->unsignedBigInteger('heart_count')->default(0);   // denormalized
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['wheel_challenge_id', 'created_at']);
        });

        Schema::create('wheel_response_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wheel_response_id')->constrained('wheel_responses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // laugh | heart
            $table->timestamps();

            $table->unique(['wheel_response_id', 'user_id', 'type']);
        });

        // Group room messages ("أوضة لفّة النهاردة").
        Schema::create('wheel_room_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wheel_challenge_id')->constrained('wheel_challenges')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index(['wheel_challenge_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wheel_room_messages');
        Schema::dropIfExists('wheel_response_reactions');
        Schema::dropIfExists('wheel_responses');
        Schema::dropIfExists('wheel_spins');
        Schema::dropIfExists('wheel_challenges');
    }
};
