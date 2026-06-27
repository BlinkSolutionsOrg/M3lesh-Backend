<?php

namespace App\Filament\Resources\Community;

use App\Filament\Resources\Community\Pages\ListCommunitySeasons;
use App\Filament\Resources\Community\Pages\ViewCommunitySeason;
use App\Filament\Resources\Community\RelationManagers\CommunityMilestonesRelationManager;
use App\Filament\Resources\Community\Schemas\CommunitySeasonForm;
use App\Filament\Resources\Community\Tables\CommunitySeasonsTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Community\CommunitySeason;
use BackedEnum;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommunitySeasonResource extends Resource
{
    protected static ?string $model = CommunitySeason::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $recordTitleAttribute = 'name_ar';

    protected static ?string $slug = 'community-seasons';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.community_season');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.community_season_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.community_season');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_community_seasons');
    }

    public static function form(Schema $schema): Schema
    {
        return CommunitySeasonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommunitySeasonsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.community_season.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('name_ar')
                            ->label(__('filament.community_season.name_ar'))
                            ->placeholder('—'),
                        TextEntry::make('name_en')
                            ->label(__('filament.community_season.name_en'))
                            ->placeholder('—'),
                        TextEntry::make('goal_leaves')
                            ->label(__('filament.community_season.goal_leaves'))
                            ->numeric(),
                        TextEntry::make('leaves_count')
                            ->label(__('filament.community_season.leaves_count'))
                            ->numeric(),
                        TextEntry::make('starts_at')
                            ->label(__('filament.community_season.starts_at'))
                            ->dateTime()
                            ->placeholder('—'),
                        TextEntry::make('ends_at')
                            ->label(__('filament.community_season.ends_at'))
                            ->dateTime()
                            ->placeholder('—'),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommunityMilestonesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommunitySeasons::route('/'),
            'view' => ViewCommunitySeason::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_community_seasons');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_community_seasons');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_community_seasons');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_community_seasons');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_community_seasons');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_community_seasons');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_community_seasons');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
