<?php

namespace App\Filament\Resources\Mood;

use App\Filament\Resources\Mood\Pages\ListMoods;
use App\Filament\Resources\Mood\Pages\ViewMood;
use App\Filament\Resources\Mood\Schemas\MoodForm;
use App\Filament\Resources\Mood\Tables\MoodsTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Mood\Mood;
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

class MoodResource extends Resource
{
    protected static ?string $model = Mood::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFaceSmile;

    protected static ?string $recordTitleAttribute = 'label_ar';

    protected static ?string $slug = 'moods';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.mood');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.mood_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.mood');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_moods');
    }

    public static function form(Schema $schema): Schema
    {
        return MoodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MoodsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.mood.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('key')
                            ->label(__('filament.mood.key'))
                            ->placeholder('—'),
                        TextEntry::make('label_ar')
                            ->label(__('filament.mood.label_ar'))
                            ->placeholder('—'),
                        TextEntry::make('label_en')
                            ->label(__('filament.mood.label_en'))
                            ->placeholder('—'),
                        TextEntry::make('face_mood')
                            ->label(__('filament.mood.face_mood'))
                            ->placeholder('—'),
                        ColorEntry::make('color')
                            ->label(__('filament.mood.color'))
                            ->placeholder('—'),
                        ColorEntry::make('bg_color')
                            ->label(__('filament.mood.bg_color'))
                            ->placeholder('—'),
                        TextEntry::make('sort_order')
                            ->label(__('filament.mood.sort_order')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMoods::route('/'),
            'view' => ViewMood::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_moods');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_moods');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_moods');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_moods');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_moods');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_moods');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_moods');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
