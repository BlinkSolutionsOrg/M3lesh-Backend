<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // «رفيق معلش» — one active listening conversation per user.
        Schema::create('companion_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->unsignedInteger('message_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
        });

        // Chat lines: role = user|cat.
        Schema::create('companion_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('companion_conversation_id')->constrained('companion_conversations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role');
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['companion_conversation_id', 'created_at']);
        });

        // Suggestion chips (admin-authored, localized).
        Schema::create('companion_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('text_ar');
            $table->string('text_en');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });

        // The cat reply pool (admin-authored). One greeting + canned replies, weighted.
        Schema::create('companion_replies', function (Blueprint $table) {
            $table->id();
            $table->string('text_ar');
            $table->string('text_en');
            $table->boolean('is_greeting')->default(false);
            $table->unsignedInteger('weight')->default(1);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'is_greeting']);
        });

        // Singleton settings row: help banner + hotline + presence label.
        Schema::create('companion_settings', function (Blueprint $table) {
            $table->id();
            $table->string('help_banner_title_ar')->nullable();
            $table->string('help_banner_title_en')->nullable();
            $table->text('help_banner_body_ar')->nullable();
            $table->text('help_banner_body_en')->nullable();
            $table->string('hotline_number')->nullable();
            $table->string('presence_label_ar')->nullable();
            $table->string('presence_label_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companion_settings');
        Schema::dropIfExists('companion_replies');
        Schema::dropIfExists('companion_suggestions');
        Schema::dropIfExists('companion_messages');
        Schema::dropIfExists('companion_conversations');
    }
};
