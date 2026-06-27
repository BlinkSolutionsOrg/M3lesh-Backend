<?php

namespace App\Filament\Resources\CircleChallenge;

use App\Filament\Resources\CircleChallenge\Pages\ListCircleChallenges;
use App\Filament\Resources\CircleChallenge\Pages\ViewCircleChallenge;
use App\Filament\Resources\CircleChallenge\RelationManagers\CircleChallengeMessagesRelationManager;
use App\Filament\Resources\CircleChallenge\RelationManagers\CircleChallengeStepsRelationManager;
use App\Filament\Resources\CircleChallenge\Schemas\CircleChallengeForm;
use App\Filament\Resources\CircleChallenge\Tables\CircleChallengesTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Circle\CircleChallenge;
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

class CircleChallengeResource extends Resource
{
    protected static ?string $model = CircleChallenge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $recordTitleAttribute = 'title_ar';

    protected static ?string $slug = 'circle-challenges';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.circle_challenge');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.circle_challenge_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.circle_challenge');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_circle_challenges');
    }

    public static function form(Schema $schema): Schema
    {
        return CircleChallengeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CircleChallengesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.circle_challenge.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('circle.name_ar')
                            ->label(__('filament.circle_challenge.circle'))
                            ->placeholder('—'),
                        TextEntry::make('participants_count')
                            ->label(__('filament.circle_challenge.participants_count')),
                        TextEntry::make('title_ar')
                            ->label(__('filament.circle_challenge.title_ar'))
                            ->placeholder('—'),
                        TextEntry::make('title_en')
                            ->label(__('filament.circle_challenge.title_en'))
                            ->placeholder('—'),
                        TextEntry::make('description_ar')
                            ->label(__('filament.circle_challenge.description_ar'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('description_en')
                            ->label(__('filament.circle_challenge.description_en'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CircleChallengeStepsRelationManager::class,
            CircleChallengeMessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCircleChallenges::route('/'),
            'view' => ViewCircleChallenge::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_circle_challenges');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_circle_challenges');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_circle_challenges');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_circle_challenges');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_circle_challenges');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_circle_challenges');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_circle_challenges');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
