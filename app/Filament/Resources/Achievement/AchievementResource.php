<?php

namespace App\Filament\Resources\Achievement;

use App\Filament\Resources\Achievement\Pages\ListAchievements;
use App\Filament\Resources\Achievement\Pages\ViewAchievement;
use App\Filament\Resources\Achievement\Schemas\AchievementForm;
use App\Filament\Resources\Achievement\Tables\AchievementsTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Space\Achievement;
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

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $recordTitleAttribute = 'name_ar';

    protected static ?string $slug = 'achievements';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.achievement');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.achievement_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.achievement');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_achievements');
    }

    public static function form(Schema $schema): Schema
    {
        return AchievementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AchievementsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.achievement.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('key')
                            ->label(__('filament.achievement.key'))
                            ->placeholder('—'),
                        TextEntry::make('icon')
                            ->label(__('filament.achievement.icon'))
                            ->placeholder('—'),
                        TextEntry::make('name_ar')
                            ->label(__('filament.achievement.name_ar'))
                            ->placeholder('—'),
                        TextEntry::make('name_en')
                            ->label(__('filament.achievement.name_en'))
                            ->placeholder('—'),
                        TextEntry::make('criterion_ar')
                            ->label(__('filament.achievement.criterion_ar'))
                            ->placeholder('—'),
                        TextEntry::make('criterion_en')
                            ->label(__('filament.achievement.criterion_en'))
                            ->placeholder('—'),
                        TextEntry::make('sort_order')
                            ->label(__('filament.achievement.sort_order')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAchievements::route('/'),
            'view' => ViewAchievement::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_achievements');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_achievements');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_achievements');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_achievements');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_achievements');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_achievements');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_achievements');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
