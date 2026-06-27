<?php

namespace App\Providers;

use App\Models\Circle\Circle;
use App\Models\Community\CommunitySeason;
use App\Models\Companion\CompanionReply;
use App\Models\Companion\CompanionSuggestion;
use App\Models\Help\HelpAsk;
use App\Models\Mood\Mood;
use App\Models\Post\Post;
use App\Models\Post\PostCommentPreset;
use App\Models\Space\Achievement;
use App\Models\Space\DailyCardTip;
use App\Models\Space\RoomDecoration;
use App\Models\Story\Story;
use App\Models\User\Admin;
use App\Models\Wheel\WheelChallenge;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind LanguageSwitch as singleton to ensure configuration persists
        $this->app->singleton(LanguageSwitch::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('admin_post', fn (string $value) => Post::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('comment_preset', fn (string $value) => PostCommentPreset::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_circle', fn (string $value) => Circle::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_help_ask', fn (string $value) => HelpAsk::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_story', fn (string $value) => Story::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_wheel_challenge', fn (string $value) => WheelChallenge::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_mood', fn (string $value) => Mood::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_daily_card_tip', fn (string $value) => DailyCardTip::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_achievement', fn (string $value) => Achievement::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_room_decoration', fn (string $value) => RoomDecoration::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_companion_suggestion', fn (string $value) => CompanionSuggestion::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_companion_reply', fn (string $value) => CompanionReply::withTrashed()->whereKey($value)->firstOrFail());
        Route::bind('admin_community_season', fn (string $value) => CommunitySeason::withTrashed()->whereKey($value)->firstOrFail());

        // Super Admin (admin_type = super_admin) bypasses all permission checks everywhere:
        // Filament (canViewAny, canCreate, canEdit, …) and API (authorize, $user->can(…)).
        Gate::before(function ($user, $ability) {
            if ($user instanceof Admin && $user->admin_type === Admin::TYPE_SUPER_ADMIN) {
                return true;
            }

            return null;
        });

        // Configure LanguageSwitch - configure the singleton instance
        // This must be done in boot() and will persist when LanguageSwitch::boot() is called
        // Using default renderHook 'panels::global-search.after' - it works even when global search is disabled
        LanguageSwitch::make()
            ->locales(['ar', 'en'])  // Arabic and English
            ->labels([
                'en' => 'English',  // When current is Arabic, show "English" (target language)
                'ar' => 'العربية',     // When current is English, show "العربية" (target language)
            ])
            ->circular()  // Show only the opposite/inactive language directly (not dropdown)
            ->visible(insidePanels: true, outsidePanels: false)  // Only show in panels
            ->excludes([]);  // Don't exclude any panels - show in all panels
        // Using default renderHook 'panels::global-search.after' - works even with global search disabled
    }
}
