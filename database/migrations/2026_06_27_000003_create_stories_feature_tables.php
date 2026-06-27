<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Story categories (admin-managed): الكل، لحظات حلوة، فضفضة، مكاسب…
        Schema::create('story_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();              // stable key e.g. "sweet"
            $table->string('label_ar');
            $table->string('label_en');
            $table->string('color');                       // hex e.g. "#9C7A2E"
            $table->string('bg_color');                    // hex e.g. "#FFFFFF"
            $table->string('border_color')->nullable();    // hex e.g. "#F0E0BE"
            $table->string('reaction_emoji')->nullable();  // emoji rendered as plain text
            $table->string('reaction_label_ar')->nullable();
            $table->string('reaction_label_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();      // audit, not constrained
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });

        // Stories ("الحكايات"): global feed shares with 💜 hearts and comments.
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('story_category_id')->nullable()->constrained('story_categories')->nullOnDelete();
            $table->foreignId('circle_id')->nullable()->constrained('circles')->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('body');
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedBigInteger('hearts_count')->default(0);    // denormalized
            $table->unsignedBigInteger('comments_count')->default(0);  // denormalized
            $table->timestamp('last_activity_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['story_category_id', 'created_at']);
            $table->index(['circle_id', 'created_at']);
            $table->index(['created_at']);
        });

        Schema::create('story_hearts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained('stories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['story_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_hearts');
        Schema::dropIfExists('stories');
        Schema::dropIfExists('story_categories');
    }
};
