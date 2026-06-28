<?php

use App\Http\Controllers\Api\Admin\Auth\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\Auth\DeviceController as AdminDeviceController;
use App\Http\Controllers\Api\Admin\Auth\PermissionController as AdminPermissionController;
use App\Http\Controllers\Api\Admin\Auth\RoleController as AdminRoleController;
use App\Http\Controllers\Api\Admin\Circle\CircleController as AdminCircleController;
use App\Http\Controllers\Api\Admin\Community\CommunitySeasonController as AdminCommunitySeasonController;
use App\Http\Controllers\Api\Admin\Companion\CompanionReplyController as AdminCompanionReplyController;
use App\Http\Controllers\Api\Admin\Companion\CompanionSettingsController as AdminCompanionSettingsController;
use App\Http\Controllers\Api\Admin\Companion\CompanionSuggestionController as AdminCompanionSuggestionController;
use App\Http\Controllers\Api\Admin\Help\HelpAskController as AdminHelpAskController;
use App\Http\Controllers\Api\Admin\Story\StoryController as AdminStoryController;
use App\Http\Controllers\Api\Admin\Mood\MoodController as AdminMoodController;
use App\Http\Controllers\Api\Admin\Space\AchievementController as AdminAchievementController;
use App\Http\Controllers\Api\Admin\Space\DailyCardTipController as AdminDailyCardTipController;
use App\Http\Controllers\Api\Admin\Space\RoomDecorationController as AdminRoomDecorationController;
use App\Http\Controllers\Api\Admin\Wheel\WheelChallengeController as AdminWheelChallengeController;
use App\Http\Controllers\Api\Admin\ContentPage\ContentPageController as AdminContentPageController;
use App\Http\Controllers\Api\Admin\Notifications\NotificationBroadcastController as AdminNotificationBroadcastController;
use App\Http\Controllers\Api\Admin\Notifications\NotificationController as AdminNotificationController;
use App\Http\Controllers\Api\Admin\Post\PostCommentPresetController as AdminPostCommentPresetController;
use App\Http\Controllers\Api\Admin\Post\PostController as AdminPostController;
use App\Http\Controllers\Api\Admin\SupportTicket\SupportTicketController as AdminSupportTicketController;
use App\Http\Controllers\Api\Admin\User\UserController as AdminUserController;
use App\Http\Controllers\Api\User\Auth\AuthController as UserAuthController;
use App\Http\Controllers\Api\User\Auth\DeviceController as UserDeviceController;
use App\Http\Controllers\Api\User\Avatar\AvatarController as UserAvatarController;
use App\Http\Controllers\Api\User\Circle\CircleChallengeController as UserCircleChallengeController;
use App\Http\Controllers\Api\User\Circle\CircleController as UserCircleController;
use App\Http\Controllers\Api\User\Community\CommunityController as UserCommunityController;
use App\Http\Controllers\Api\User\Companion\CompanionController as UserCompanionController;
use App\Http\Controllers\Api\User\Circle\CircleIcebreakerController as UserCircleIcebreakerController;
use App\Http\Controllers\Api\User\Circle\CircleStoryController as UserCircleStoryController;
use App\Http\Controllers\Api\User\Circle\CircleWinController as UserCircleWinController;
use App\Http\Controllers\Api\User\Help\HelpAskController as UserHelpAskController;
use App\Http\Controllers\Api\User\Help\HelpReplyController as UserHelpReplyController;
use App\Http\Controllers\Api\User\Auth\ForgotPasswordController as UserForgotPasswordController;
use App\Http\Controllers\Api\User\Home\HomeController as UserHomeController;
use App\Http\Controllers\Api\User\Search\SearchController as UserSearchController;
use App\Http\Controllers\Api\User\Story\StoryCategoryController as UserStoryCategoryController;
use App\Http\Controllers\Api\User\Story\StoryController as UserStoryController;
use App\Http\Controllers\Api\User\Mood\MoodController as UserMoodController;
use App\Http\Controllers\Api\User\Space\AchievementController as UserAchievementController;
use App\Http\Controllers\Api\User\Space\DailyCardController as UserDailyCardController;
use App\Http\Controllers\Api\User\Space\FutureLetterController as UserFutureLetterController;
use App\Http\Controllers\Api\User\Space\GratitudeNoteController as UserGratitudeNoteController;
use App\Http\Controllers\Api\User\Space\JournalEntryController as UserJournalEntryController;
use App\Http\Controllers\Api\User\Space\RoomDecorationController as UserRoomDecorationController;
use App\Http\Controllers\Api\User\Space\SpaceController as UserSpaceController;
use App\Http\Controllers\Api\User\Wheel\WheelController as UserWheelController;
use App\Http\Controllers\Api\User\ContentPage\ContentPageController as UserContentPageController;
use App\Http\Controllers\Api\User\Notifications\NotificationController as UserNotificationController;
use App\Http\Controllers\Api\User\Post\PostCommentPresetController as UserPostCommentPresetController;
use App\Http\Controllers\Api\User\Post\PostController as UserPostController;
use App\Http\Controllers\Api\User\SupportTicket\SupportTicketController as UserSupportTicketController;
use App\Http\Controllers\Api\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function (): void {
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('devices', [AdminDeviceController::class, 'index']);

        Route::get('roles', [AdminRoleController::class, 'index']);
        Route::post('roles', [AdminRoleController::class, 'store']);
        Route::get('roles/{role}', [AdminRoleController::class, 'show']);
        Route::put('roles/{role}', [AdminRoleController::class, 'update']);
        Route::delete('roles/{role}', [AdminRoleController::class, 'destroy']);

        Route::get('permissions', [AdminPermissionController::class, 'index']);
        Route::get('permissions/{permission}', [AdminPermissionController::class, 'show']);

        Route::get('users', [AdminUserController::class, 'index']);
        Route::post('users', [AdminUserController::class, 'store']);
        Route::get('users/{user}', [AdminUserController::class, 'show']);
        Route::put('users/{user}', [AdminUserController::class, 'update']);
        Route::delete('users/{user}', [AdminUserController::class, 'destroy']);

        Route::get('notifications', [AdminNotificationController::class, 'index']);
        Route::get('notifications/unread-count', [AdminNotificationController::class, 'unreadCount']);
        Route::get('notifications/{notification}', [AdminNotificationController::class, 'show']);
        Route::post('notifications/send', [AdminNotificationController::class, 'send']);
        Route::post('notifications/{notification}/read', [AdminNotificationController::class, 'markRead']);
        Route::post('notifications/read-all', [AdminNotificationController::class, 'readAll']);

        Route::get('notification-broadcasts', [AdminNotificationBroadcastController::class, 'index']);
        Route::get('notification-broadcasts/{broadcast}', [AdminNotificationBroadcastController::class, 'show']);
        Route::post('notification-broadcasts/send', [AdminNotificationBroadcastController::class, 'send']);

        Route::get('support-tickets', [AdminSupportTicketController::class, 'index']);
        Route::post('support-tickets', [AdminSupportTicketController::class, 'store']);
        Route::get('support-tickets/{support_ticket}', [AdminSupportTicketController::class, 'show']);
        Route::put('support-tickets/{support_ticket}', [AdminSupportTicketController::class, 'update']);
        Route::delete('support-tickets/{support_ticket}', [AdminSupportTicketController::class, 'destroy']);
        Route::put('support-tickets/{support_ticket}/status', [AdminSupportTicketController::class, 'updateStatus']);
        Route::put('support-tickets/{support_ticket}/priority', [AdminSupportTicketController::class, 'updatePriority']);
        Route::post('support-tickets/{support_ticket}/logs', [AdminSupportTicketController::class, 'storeLog']);

        Route::prefix('content-pages')->group(function (): void {
            Route::get('/', [AdminContentPageController::class, 'index']);
            Route::post('/', [AdminContentPageController::class, 'store']);
            Route::get('{content_page}', [AdminContentPageController::class, 'show']);
            Route::put('{content_page}', [AdminContentPageController::class, 'update']);
            Route::delete('{content_page}', [AdminContentPageController::class, 'destroy']);
        });

        Route::get('circles', [AdminCircleController::class, 'index']);
        Route::post('circles', [AdminCircleController::class, 'store']);
        Route::post('circles/{admin_circle}/restore', [AdminCircleController::class, 'restore']);
        Route::delete('circles/{admin_circle}/force', [AdminCircleController::class, 'forceDestroy']);
        Route::get('circles/{admin_circle}', [AdminCircleController::class, 'show']);
        Route::put('circles/{admin_circle}', [AdminCircleController::class, 'update']);
        Route::delete('circles/{admin_circle}', [AdminCircleController::class, 'destroy']);

        // Help asks — admin moderation
        Route::get('help-asks', [AdminHelpAskController::class, 'index']);
        Route::post('help-asks', [AdminHelpAskController::class, 'store']);
        Route::post('help-asks/{admin_help_ask}/restore', [AdminHelpAskController::class, 'restore']);
        Route::delete('help-asks/{admin_help_ask}/force', [AdminHelpAskController::class, 'forceDestroy']);
        Route::get('help-asks/{admin_help_ask}', [AdminHelpAskController::class, 'show']);
        Route::put('help-asks/{admin_help_ask}', [AdminHelpAskController::class, 'update']);
        Route::delete('help-asks/{admin_help_ask}', [AdminHelpAskController::class, 'destroy']);

        // Stories — admin moderation
        Route::get('stories', [AdminStoryController::class, 'index']);
        Route::post('stories/{admin_story}/restore', [AdminStoryController::class, 'restore']);
        Route::delete('stories/{admin_story}/force', [AdminStoryController::class, 'forceDestroy']);
        Route::get('stories/{admin_story}', [AdminStoryController::class, 'show']);
        Route::put('stories/{admin_story}', [AdminStoryController::class, 'update']);
        Route::delete('stories/{admin_story}', [AdminStoryController::class, 'destroy']);

        // Wheel challenges — admin CRUD
        Route::get('wheel-challenges', [AdminWheelChallengeController::class, 'index']);
        Route::post('wheel-challenges', [AdminWheelChallengeController::class, 'store']);
        Route::post('wheel-challenges/{admin_wheel_challenge}/restore', [AdminWheelChallengeController::class, 'restore']);
        Route::delete('wheel-challenges/{admin_wheel_challenge}/force', [AdminWheelChallengeController::class, 'forceDestroy']);
        Route::get('wheel-challenges/{admin_wheel_challenge}', [AdminWheelChallengeController::class, 'show']);
        Route::put('wheel-challenges/{admin_wheel_challenge}', [AdminWheelChallengeController::class, 'update']);
        Route::delete('wheel-challenges/{admin_wheel_challenge}', [AdminWheelChallengeController::class, 'destroy']);

        // Moods — admin CRUD
        Route::get('moods', [AdminMoodController::class, 'index']);
        Route::post('moods', [AdminMoodController::class, 'store']);
        Route::post('moods/{admin_mood}/restore', [AdminMoodController::class, 'restore']);
        Route::delete('moods/{admin_mood}/force', [AdminMoodController::class, 'forceDestroy']);
        Route::get('moods/{admin_mood}', [AdminMoodController::class, 'show']);
        Route::put('moods/{admin_mood}', [AdminMoodController::class, 'update']);
        Route::delete('moods/{admin_mood}', [AdminMoodController::class, 'destroy']);

        // Space — daily card tips CRUD
        Route::get('daily-card-tips', [AdminDailyCardTipController::class, 'index']);
        Route::post('daily-card-tips', [AdminDailyCardTipController::class, 'store']);
        Route::post('daily-card-tips/{admin_daily_card_tip}/restore', [AdminDailyCardTipController::class, 'restore']);
        Route::delete('daily-card-tips/{admin_daily_card_tip}/force', [AdminDailyCardTipController::class, 'forceDestroy']);
        Route::get('daily-card-tips/{admin_daily_card_tip}', [AdminDailyCardTipController::class, 'show']);
        Route::put('daily-card-tips/{admin_daily_card_tip}', [AdminDailyCardTipController::class, 'update']);
        Route::delete('daily-card-tips/{admin_daily_card_tip}', [AdminDailyCardTipController::class, 'destroy']);

        // Space — achievements CRUD
        Route::get('achievements', [AdminAchievementController::class, 'index']);
        Route::post('achievements', [AdminAchievementController::class, 'store']);
        Route::post('achievements/{admin_achievement}/restore', [AdminAchievementController::class, 'restore']);
        Route::delete('achievements/{admin_achievement}/force', [AdminAchievementController::class, 'forceDestroy']);
        Route::get('achievements/{admin_achievement}', [AdminAchievementController::class, 'show']);
        Route::put('achievements/{admin_achievement}', [AdminAchievementController::class, 'update']);
        Route::delete('achievements/{admin_achievement}', [AdminAchievementController::class, 'destroy']);

        // Space — room decorations CRUD
        Route::get('room-decorations', [AdminRoomDecorationController::class, 'index']);
        Route::post('room-decorations', [AdminRoomDecorationController::class, 'store']);
        Route::post('room-decorations/{admin_room_decoration}/restore', [AdminRoomDecorationController::class, 'restore']);
        Route::delete('room-decorations/{admin_room_decoration}/force', [AdminRoomDecorationController::class, 'forceDestroy']);
        Route::get('room-decorations/{admin_room_decoration}', [AdminRoomDecorationController::class, 'show']);
        Route::put('room-decorations/{admin_room_decoration}', [AdminRoomDecorationController::class, 'update']);
        Route::delete('room-decorations/{admin_room_decoration}', [AdminRoomDecorationController::class, 'destroy']);

        // Companion suggestions — admin CRUD
        Route::get('companion-suggestions', [AdminCompanionSuggestionController::class, 'index']);
        Route::post('companion-suggestions', [AdminCompanionSuggestionController::class, 'store']);
        Route::post('companion-suggestions/{admin_companion_suggestion}/restore', [AdminCompanionSuggestionController::class, 'restore']);
        Route::delete('companion-suggestions/{admin_companion_suggestion}/force', [AdminCompanionSuggestionController::class, 'forceDestroy']);
        Route::get('companion-suggestions/{admin_companion_suggestion}', [AdminCompanionSuggestionController::class, 'show']);
        Route::put('companion-suggestions/{admin_companion_suggestion}', [AdminCompanionSuggestionController::class, 'update']);
        Route::delete('companion-suggestions/{admin_companion_suggestion}', [AdminCompanionSuggestionController::class, 'destroy']);

        // Companion replies — admin CRUD
        Route::get('companion-replies', [AdminCompanionReplyController::class, 'index']);
        Route::post('companion-replies', [AdminCompanionReplyController::class, 'store']);
        Route::post('companion-replies/{admin_companion_reply}/restore', [AdminCompanionReplyController::class, 'restore']);
        Route::delete('companion-replies/{admin_companion_reply}/force', [AdminCompanionReplyController::class, 'forceDestroy']);
        Route::get('companion-replies/{admin_companion_reply}', [AdminCompanionReplyController::class, 'show']);
        Route::put('companion-replies/{admin_companion_reply}', [AdminCompanionReplyController::class, 'update']);
        Route::delete('companion-replies/{admin_companion_reply}', [AdminCompanionReplyController::class, 'destroy']);

        // Companion settings — singleton
        Route::get('companion-settings', [AdminCompanionSettingsController::class, 'show']);
        Route::put('companion-settings', [AdminCompanionSettingsController::class, 'update']);

        // Community seasons — admin CRUD
        Route::get('community-seasons', [AdminCommunitySeasonController::class, 'index']);
        Route::post('community-seasons', [AdminCommunitySeasonController::class, 'store']);
        Route::post('community-seasons/{admin_community_season}/restore', [AdminCommunitySeasonController::class, 'restore']);
        Route::delete('community-seasons/{admin_community_season}/force', [AdminCommunitySeasonController::class, 'forceDestroy']);
        Route::get('community-seasons/{admin_community_season}', [AdminCommunitySeasonController::class, 'show']);
        Route::put('community-seasons/{admin_community_season}', [AdminCommunitySeasonController::class, 'update']);
        Route::delete('community-seasons/{admin_community_season}', [AdminCommunitySeasonController::class, 'destroy']);

        Route::get('posts', [AdminPostController::class, 'index']);
        Route::post('posts', [AdminPostController::class, 'store']);
        Route::post('posts/{admin_post}/restore', [AdminPostController::class, 'restore']);
        Route::delete('posts/{admin_post}/force', [AdminPostController::class, 'forceDestroy']);
        Route::get('posts/{admin_post}/comments', [AdminPostController::class, 'commentsIndex']);
        Route::delete('posts/{admin_post}/comments/{comment}', [AdminPostController::class, 'destroyComment']);
        Route::get('posts/{admin_post}/likes', [AdminPostController::class, 'likesIndex']);
        Route::delete('posts/{admin_post}/likes/{like}', [AdminPostController::class, 'destroyLike']);
        Route::get('posts/{admin_post}', [AdminPostController::class, 'show']);
        Route::put('posts/{admin_post}', [AdminPostController::class, 'update']);
        Route::delete('posts/{admin_post}', [AdminPostController::class, 'destroy']);

        Route::prefix('post-comment-presets')->group(function (): void {
            Route::get('/', [AdminPostCommentPresetController::class, 'index']);
            Route::post('/', [AdminPostCommentPresetController::class, 'store']);
            Route::post('{comment_preset}/restore', [AdminPostCommentPresetController::class, 'restore']);
            Route::delete('{comment_preset}/force', [AdminPostCommentPresetController::class, 'forceDestroy']);
            Route::get('{comment_preset}', [AdminPostCommentPresetController::class, 'show']);
            Route::put('{comment_preset}', [AdminPostCommentPresetController::class, 'update']);
            Route::delete('{comment_preset}', [AdminPostCommentPresetController::class, 'destroy']);
        });
    });
});

Route::prefix('user')->group(function (): void {
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    // Password recovery (public, throttled).
    Route::post('forgot-password', [UserForgotPasswordController::class, 'sendCode'])
        ->middleware('throttle:6,1');
    Route::post('reset-password', [UserForgotPasswordController::class, 'reset'])
        ->middleware('throttle:6,1');

    Route::middleware('optional.sanctum')->group(function (): void {
        Route::get('users/{user}', [UserProfileController::class, 'show'])->whereNumber('user');
        Route::get('posts', [UserPostController::class, 'index']);
        Route::get('posts/{post}', [UserPostController::class, 'show'])->whereNumber('post');
        Route::get('posts/{post}/comments', [UserPostController::class, 'commentsIndex'])->whereNumber('post');

        // Circles — public reads
        Route::get('circles', [UserCircleController::class, 'index']);
        Route::get('circles/{circle}', [UserCircleController::class, 'show'])->whereNumber('circle');
        Route::get('circles/{circle}/wins', [UserCircleWinController::class, 'index'])->whereNumber('circle');
        Route::get('circles/{circle}/stories', [UserCircleStoryController::class, 'index'])->whereNumber('circle');
        Route::get('circles/{circle}/icebreakers', [UserCircleIcebreakerController::class, 'index'])->whereNumber('circle');
        Route::get('circles/{circle}/challenge', [UserCircleChallengeController::class, 'show'])->whereNumber('circle');
        Route::get('circles/{circle}/challenge/messages', [UserCircleChallengeController::class, 'messagesIndex'])->whereNumber('circle');

        // Help (محتاج رأيكم) — public reads
        Route::get('help/asks', [UserHelpAskController::class, 'index']);
        Route::get('help/asks/{ask}', [UserHelpAskController::class, 'show'])->whereNumber('ask');
        Route::get('help/asks/{ask}/replies', [UserHelpReplyController::class, 'index'])->whereNumber('ask');

        // Stories — public reads (literal /categories before the {story} wildcard)
        Route::get('stories/categories', [UserStoryCategoryController::class, 'index']);
        Route::get('stories', [UserStoryController::class, 'index']);
        Route::get('stories/{story}', [UserStoryController::class, 'show'])->whereNumber('story');

        // Wheel — read endpoints operate on the latest active challenge
        Route::get('wheel/today', [UserWheelController::class, 'today']);
        Route::get('wheel/today/responses', [UserWheelController::class, 'responsesIndex']);
        Route::get('wheel/today/room/messages', [UserWheelController::class, 'roomMessagesIndex']);

        // Mood — public catalog
        Route::get('moods', [UserMoodController::class, 'index']);

        // Space — catalogs (optional auth)
        Route::get('daily-card-tips', [UserDailyCardController::class, 'tips']);
        Route::get('achievements', [UserAchievementController::class, 'index']);
        Route::get('room-decorations', [UserRoomDecorationController::class, 'index']);

        // Companion (رفيق معلش) — catalog (optional auth)
        Route::get('companion/suggestions', [UserCompanionController::class, 'suggestions']);
        Route::get('companion/config', [UserCompanionController::class, 'config']);

        // Community (شجرة الدعم) — overview (optional auth)
        Route::get('community/overview', [UserCommunityController::class, 'overview']);

        // Home dashboard aggregate (optional auth)
        Route::get('home', [UserHomeController::class, 'index']);

        // Unified search (optional auth)
        Route::get('search', [UserSearchController::class, 'index']);
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('me', [UserAuthController::class, 'me']);
        Route::get('avatars', [UserAvatarController::class, 'index']);
        Route::put('me', [UserAuthController::class, 'update']);
        Route::delete('me', [UserAuthController::class, 'destroy']);
        Route::post('change-password', [UserAuthController::class, 'changePassword']);
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::get('devices', [UserDeviceController::class, 'index']);

        Route::get('notifications', [UserNotificationController::class, 'index']);
        Route::get('notifications/unread-count', [UserNotificationController::class, 'unreadCount']);
        Route::get('notifications/{notification}', [UserNotificationController::class, 'show']);
        Route::post('notifications/{notification}/read', [UserNotificationController::class, 'markRead']);
        Route::post('notifications/read-all', [UserNotificationController::class, 'readAll']);

        Route::get('support-tickets', [UserSupportTicketController::class, 'index']);
        Route::get('support-tickets/{support_ticket}', [UserSupportTicketController::class, 'show']);
        Route::post('support-tickets/{support_ticket}/logs', [UserSupportTicketController::class, 'storeLog']);

        Route::get('comment-presets', [UserPostCommentPresetController::class, 'index']);
        Route::get('posts/mine', [UserPostController::class, 'mine']);
        Route::post('posts', [UserPostController::class, 'store']);
        Route::put('posts/{post}', [UserPostController::class, 'update']);
        Route::delete('posts/{post}', [UserPostController::class, 'destroy']);
        Route::post('posts/{post}/like', [UserPostController::class, 'toggleLike']);
        Route::post('posts/{post}/comments', [UserPostController::class, 'commentsStore']);
        Route::delete('posts/{post}/comments/{comment}', [UserPostController::class, 'commentsDestroy'])->whereNumber('post')->whereNumber('comment');
        Route::post('posts/{post}/report', [UserPostController::class, 'report']);

        // Circles — authenticated writes
        Route::post('circles/{circle}/join', [UserCircleController::class, 'join'])->whereNumber('circle');
        Route::delete('circles/{circle}/join', [UserCircleController::class, 'leave'])->whereNumber('circle');

        Route::post('circles/{circle}/wins', [UserCircleWinController::class, 'store'])->whereNumber('circle');
        Route::post('circles/{circle}/wins/{win}/cheer', [UserCircleWinController::class, 'cheer'])->whereNumber('circle')->whereNumber('win');
        Route::delete('circles/{circle}/wins/{win}', [UserCircleWinController::class, 'destroy'])->whereNumber('circle')->whereNumber('win');

        Route::post('circles/{circle}/stories', [UserCircleStoryController::class, 'store'])->whereNumber('circle');
        Route::post('circles/{circle}/stories/{story}/heart', [UserCircleStoryController::class, 'heart'])->whereNumber('circle')->whereNumber('story');
        Route::delete('circles/{circle}/stories/{story}', [UserCircleStoryController::class, 'destroy'])->whereNumber('circle')->whereNumber('story');

        Route::post('circles/{circle}/challenge/steps/{step}', [UserCircleChallengeController::class, 'toggleStep'])->whereNumber('circle')->whereNumber('step');
        Route::post('circles/{circle}/challenge/messages', [UserCircleChallengeController::class, 'messagesStore'])->whereNumber('circle');

        // Help (محتاج رأيكم) — authenticated writes
        Route::post('help/asks', [UserHelpAskController::class, 'store']);
        Route::delete('help/asks/{ask}', [UserHelpAskController::class, 'destroy'])->whereNumber('ask');
        Route::post('help/asks/{ask}/replies', [UserHelpReplyController::class, 'store'])->whereNumber('ask');
        Route::delete('help/replies/{reply}', [UserHelpReplyController::class, 'destroy'])->whereNumber('reply');
        Route::post('help/replies/{reply}/vote', [UserHelpReplyController::class, 'vote'])->whereNumber('reply');

        // Stories — authenticated writes
        Route::post('stories', [UserStoryController::class, 'store']);
        Route::post('stories/{story}/heart', [UserStoryController::class, 'heart'])->whereNumber('story');
        Route::delete('stories/{story}', [UserStoryController::class, 'destroy'])->whereNumber('story');

        // Wheel — authenticated writes
        Route::post('wheel/today/spin', [UserWheelController::class, 'spin']);
        Route::post('wheel/today/responses', [UserWheelController::class, 'responsesStore']);
        Route::post('wheel/responses/{response}/react', [UserWheelController::class, 'react'])->whereNumber('response');
        Route::delete('wheel/responses/{response}', [UserWheelController::class, 'destroyResponse'])->whereNumber('response');
        Route::post('wheel/today/room/messages', [UserWheelController::class, 'roomMessagesStore']);

        // Mood — authenticated check-in
        Route::get('mood/today', [UserMoodController::class, 'today']);
        Route::post('mood/checkin', [UserMoodController::class, 'checkin']);
        Route::get('mood/garden', [UserMoodController::class, 'garden']);

        // Space — private user content (journal / gratitude / letters)
        Route::get('journal-entries', [UserJournalEntryController::class, 'index']);
        Route::post('journal-entries', [UserJournalEntryController::class, 'store']);
        Route::delete('journal-entries/{entry}', [UserJournalEntryController::class, 'destroy'])->whereNumber('entry');

        Route::get('gratitude-notes', [UserGratitudeNoteController::class, 'index']);
        Route::post('gratitude-notes', [UserGratitudeNoteController::class, 'store']);
        Route::delete('gratitude-notes/{note}', [UserGratitudeNoteController::class, 'destroy'])->whereNumber('note');

        Route::get('future-letters', [UserFutureLetterController::class, 'index']);
        Route::get('future-letters/{letter}', [UserFutureLetterController::class, 'show'])->whereNumber('letter');
        Route::post('future-letters', [UserFutureLetterController::class, 'store']);
        Route::post('future-letters/{letter}/open', [UserFutureLetterController::class, 'open'])->whereNumber('letter');
        Route::delete('future-letters/{letter}', [UserFutureLetterController::class, 'destroy'])->whereNumber('letter');

        // Space — overview / room / daily card / achievements
        Route::get('space', [UserSpaceController::class, 'show']);
        Route::get('space/room', [UserSpaceController::class, 'room']);
        Route::get('daily-card/today', [UserDailyCardController::class, 'today']);
        Route::post('daily-card/draw', [UserDailyCardController::class, 'draw']);
        Route::get('achievements/mine', [UserAchievementController::class, 'mine']);

        // Companion (رفيق معلش) — active conversation + messages
        Route::get('companion/conversation', [UserCompanionController::class, 'conversation']);
        Route::get('companion/messages', [UserCompanionController::class, 'messagesIndex']);
        Route::post('companion/messages', [UserCompanionController::class, 'messagesStore']);

        // Community (شجرة الدعم) — plant a support leaf
        Route::post('community/leaves', [UserCommunityController::class, 'storeLeaf']);
    });

    Route::post('support-tickets', [UserSupportTicketController::class, 'store'])->middleware('optional.sanctum');

    Route::prefix('content-pages')->group(function (): void {
        Route::get('/', [UserContentPageController::class, 'index']);
        Route::get('{content_page}', [UserContentPageController::class, 'show']);
    });
});

Route::prefix('content-pages')->group(function (): void {
    Route::get('/', [UserContentPageController::class, 'index']);
    Route::get('{content_page}', [UserContentPageController::class, 'show']);
});
