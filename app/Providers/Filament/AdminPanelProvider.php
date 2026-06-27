<?php

namespace App\Providers\Filament;

use App\Filament\Livewire\AdminDatabaseNotifications;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\CompanionSettingsPage;
use App\Filament\Resources\Achievement\AchievementResource;
use App\Filament\Resources\Admin\AdminResource;
use App\Filament\Resources\Avatar\AvatarResource;
use App\Filament\Resources\Community\CommunitySeasonResource;
use App\Filament\Resources\CompanionReply\CompanionReplyResource;
use App\Filament\Resources\CompanionSuggestion\CompanionSuggestionResource;
use App\Filament\Resources\DailyCardTip\DailyCardTipResource;
use App\Filament\Resources\Mood\MoodResource;
use App\Filament\Resources\RoomDecoration\RoomDecorationResource;
use App\Filament\Resources\Circle\CircleResource;
use App\Filament\Resources\CircleChallenge\CircleChallengeResource;
use App\Filament\Resources\ContentPage\ContentPageResource;
use App\Filament\Resources\HelpAsk\HelpAskResource;
use App\Filament\Resources\Story\StoryResource;
use App\Filament\Resources\StoryCategory\StoryCategoryResource;
use App\Filament\Resources\WheelChallenge\WheelChallengeResource;
use App\Filament\Resources\Notifications\NotificationBroadcastResource;
use App\Filament\Resources\Notifications\NotificationResource;
use App\Filament\Resources\Post\PostResource;
use App\Filament\Resources\PostCommentPreset\PostCommentPresetResource;
use App\Filament\Resources\Roles\RoleResource as ShieldRoleResource;
use App\Filament\Resources\SupportTicket\SupportTicketResource;
use App\Filament\Resources\User\UserResource;
use App\Filament\Widgets\AccessControlStatsOverview;
use App\Filament\Widgets\SupportTicketsStatsOverview;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\DatabaseNotificationsPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        // Language switch is configured in AppServiceProvider::boot()
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('dashboard')
            ->login(Login::class)
            ->default()
            ->domain(null)
            ->brandName(__('filament.navigation.brand'))
            ->brandLogoHeight('3rem')
            ->globalSearch(false)
            ->favicon(asset('images/m3lesh-favicon.png'))
            ->colors([
                'primary' => '#5F5173',
                'secondary' => '#A38DBF',
            ])
            ->databaseNotifications(
                livewireComponent: AdminDatabaseNotifications::class,
                position: DatabaseNotificationsPosition::Topbar,
            )
            ->renderHook(
                'panels::styles.after',
                fn (): string => '<style>.fi-no-database .fi-no-notification-close-btn{display:none!important}</style>',
            )
            ->sidebarCollapsibleOnDesktop()
            ->navigation(function (NavigationBuilder $navigation): NavigationBuilder {
                return $navigation
                    ->items([
                        NavigationItem::make(__('filament.navigation.dashboard'))
                            ->icon('heroicon-o-home')
                            ->url(fn (): string => Dashboard::getUrl())
                            ->visible(fn (): bool => auth()->guard('admin')->user()?->can('view_dashboard') ?? false)
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard')),
                    ])
                    ->groups([
                        NavigationGroup::make(__('filament.navigation.notifications_management'))
                            ->items([
                                ...(NotificationResource::shouldRegisterNavigation() ? NotificationResource::getNavigationItems() : []),
                                ...(NotificationBroadcastResource::shouldRegisterNavigation() ? NotificationBroadcastResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.posts_management'))
                            ->items([
                                ...(PostResource::shouldRegisterNavigation() ? PostResource::getNavigationItems() : []),
                                ...(PostCommentPresetResource::shouldRegisterNavigation() ? PostCommentPresetResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.circles_management'))
                            ->items([
                                ...(CircleResource::shouldRegisterNavigation() ? CircleResource::getNavigationItems() : []),
                                ...(CircleChallengeResource::shouldRegisterNavigation() ? CircleChallengeResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.help_management'))
                            ->items([
                                ...(HelpAskResource::shouldRegisterNavigation() ? HelpAskResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.stories_management'))
                            ->items([
                                ...(StoryResource::shouldRegisterNavigation() ? StoryResource::getNavigationItems() : []),
                                ...(StoryCategoryResource::shouldRegisterNavigation() ? StoryCategoryResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.wheel_management'))
                            ->items([
                                ...(WheelChallengeResource::shouldRegisterNavigation() ? WheelChallengeResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.mood_management'))
                            ->items([
                                ...(MoodResource::shouldRegisterNavigation() ? MoodResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.space_management'))
                            ->items([
                                ...(DailyCardTipResource::shouldRegisterNavigation() ? DailyCardTipResource::getNavigationItems() : []),
                                ...(AchievementResource::shouldRegisterNavigation() ? AchievementResource::getNavigationItems() : []),
                                ...(RoomDecorationResource::shouldRegisterNavigation() ? RoomDecorationResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.companion_management'))
                            ->items([
                                ...(CompanionSuggestionResource::shouldRegisterNavigation() ? CompanionSuggestionResource::getNavigationItems() : []),
                                ...(CompanionReplyResource::shouldRegisterNavigation() ? CompanionReplyResource::getNavigationItems() : []),
                                ...(CompanionSettingsPage::shouldRegisterNavigation() ? CompanionSettingsPage::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.community_management'))
                            ->items([
                                ...(CommunitySeasonResource::shouldRegisterNavigation() ? CommunitySeasonResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.system_services'))
                            ->items([
                                ...(SupportTicketResource::shouldRegisterNavigation() ? SupportTicketResource::getNavigationItems() : []),
                                ...(ContentPageResource::shouldRegisterNavigation() ? ContentPageResource::getNavigationItems() : []),
                            ]),
                        NavigationGroup::make(__('filament.navigation.accounts_and_permissions'))
                            ->items([
                                ...(UserResource::shouldRegisterNavigation() ? UserResource::getNavigationItems() : []),
                                ...(AvatarResource::shouldRegisterNavigation() ? AvatarResource::getNavigationItems() : []),
                                ...(AdminResource::shouldRegisterNavigation() ? AdminResource::getNavigationItems() : []),
                                ...(ShieldRoleResource::shouldRegisterNavigation() ? ShieldRoleResource::getNavigationItems() : []),
                            ]),
                    ]);
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                SupportTicketsStatsOverview::class,
                AccessControlStatsOverview::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('admin');
    }
}
