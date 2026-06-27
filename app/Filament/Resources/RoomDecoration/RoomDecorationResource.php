<?php

namespace App\Filament\Resources\RoomDecoration;

use App\Filament\Resources\RoomDecoration\Pages\ListRoomDecorations;
use App\Filament\Resources\RoomDecoration\Pages\ViewRoomDecoration;
use App\Filament\Resources\RoomDecoration\Schemas\RoomDecorationForm;
use App\Filament\Resources\RoomDecoration\Tables\RoomDecorationsTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Space\RoomDecoration;
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

class RoomDecorationResource extends Resource
{
    protected static ?string $model = RoomDecoration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static ?string $recordTitleAttribute = 'title_ar';

    protected static ?string $slug = 'room-decorations';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.room_decoration');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.room_decoration_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.room_decoration');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_room_decorations');
    }

    public static function form(Schema $schema): Schema
    {
        return RoomDecorationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoomDecorationsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.room_decoration.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('key')
                            ->label(__('filament.room_decoration.key'))
                            ->placeholder('—'),
                        TextEntry::make('title_ar')
                            ->label(__('filament.room_decoration.title_ar'))
                            ->placeholder('—'),
                        TextEntry::make('title_en')
                            ->label(__('filament.room_decoration.title_en'))
                            ->placeholder('—'),
                        TextEntry::make('required_level')
                            ->label(__('filament.room_decoration.required_level')),
                        TextEntry::make('sort_order')
                            ->label(__('filament.room_decoration.sort_order')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoomDecorations::route('/'),
            'view' => ViewRoomDecoration::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_room_decorations');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_room_decorations');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_room_decorations');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_room_decorations');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_room_decorations');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_room_decorations');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_room_decorations');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
