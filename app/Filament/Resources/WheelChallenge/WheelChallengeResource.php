<?php

namespace App\Filament\Resources\WheelChallenge;

use App\Filament\Resources\WheelChallenge\Pages\ListWheelChallenges;
use App\Filament\Resources\WheelChallenge\Pages\ViewWheelChallenge;
use App\Filament\Resources\WheelChallenge\RelationManagers\WheelResponsesRelationManager;
use App\Filament\Resources\WheelChallenge\RelationManagers\WheelRoomMessagesRelationManager;
use App\Filament\Resources\WheelChallenge\Schemas\WheelChallengeForm;
use App\Filament\Resources\WheelChallenge\Tables\WheelChallengesTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Wheel\WheelChallenge;
use BackedEnum;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WheelChallengeResource extends Resource
{
    protected static ?string $model = WheelChallenge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $recordTitleAttribute = 'title_ar';

    protected static ?string $slug = 'wheel-challenges';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.wheel_challenge');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.wheel_challenge_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.wheel_challenge');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_wheel_challenges');
    }

    public static function form(Schema $schema): Schema
    {
        return WheelChallengeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WheelChallengesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.wheel_challenge.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('segment_emoji')
                            ->label(__('filament.wheel_challenge.segment_emoji'))
                            ->placeholder('—'),
                        TextEntry::make('pill_label_ar')
                            ->label(__('filament.wheel_challenge.pill_label_ar'))
                            ->placeholder('—'),
                        TextEntry::make('pill_label_en')
                            ->label(__('filament.wheel_challenge.pill_label_en'))
                            ->placeholder('—'),
                        TextEntry::make('title_ar')
                            ->label(__('filament.wheel_challenge.title_ar'))
                            ->placeholder('—'),
                        TextEntry::make('title_en')
                            ->label(__('filament.wheel_challenge.title_en'))
                            ->placeholder('—'),
                        TextEntry::make('compose_hint_ar')
                            ->label(__('filament.wheel_challenge.compose_hint_ar'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('compose_hint_en')
                            ->label(__('filament.wheel_challenge.compose_hint_en'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('room_banner_ar')
                            ->label(__('filament.wheel_challenge.room_banner_ar'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('room_banner_en')
                            ->label(__('filament.wheel_challenge.room_banner_en'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        ColorEntry::make('color')
                            ->label(__('filament.wheel_challenge.color'))
                            ->placeholder('—'),
                        ColorEntry::make('bg_color')
                            ->label(__('filament.wheel_challenge.bg_color'))
                            ->placeholder('—'),
                        TextEntry::make('spins_count')
                            ->label(__('filament.wheel_challenge.spins_count')),
                        TextEntry::make('responses_count')
                            ->label(__('filament.wheel_challenge.responses_count')),
                        TextEntry::make('room_messages_count')
                            ->label(__('filament.wheel_challenge.room_messages_count')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            WheelResponsesRelationManager::class,
            WheelRoomMessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWheelChallenges::route('/'),
            'view' => ViewWheelChallenge::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_wheel_challenges');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_wheel_challenges');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_wheel_challenges');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_wheel_challenges');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_wheel_challenges');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_wheel_challenges');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_wheel_challenges');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
