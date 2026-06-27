<?php

namespace App\Filament\Resources\Circle;

use App\Filament\Resources\Circle\Pages\ListCircles;
use App\Filament\Resources\Circle\Pages\ViewCircle;
use App\Filament\Resources\Circle\RelationManagers\CircleIcebreakersRelationManager;
use App\Filament\Resources\Circle\RelationManagers\CircleMembersRelationManager;
use App\Filament\Resources\Circle\RelationManagers\CircleStoriesRelationManager;
use App\Filament\Resources\Circle\RelationManagers\CircleWinsRelationManager;
use App\Filament\Resources\Circle\Schemas\CircleForm;
use App\Filament\Resources\Circle\Tables\CirclesTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Circle\Circle;
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

class CircleResource extends Resource
{
    protected static ?string $model = Circle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name_ar';

    protected static ?string $slug = 'circles';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.circle');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.circle_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.circle');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_circles');
    }

    public static function form(Schema $schema): Schema
    {
        return CircleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CirclesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.circle.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('emoji')
                            ->label(__('filament.circle.emoji'))
                            ->placeholder('—'),
                        TextEntry::make('name_ar')
                            ->label(__('filament.circle.name_ar'))
                            ->placeholder('—'),
                        TextEntry::make('name_en')
                            ->label(__('filament.circle.name_en'))
                            ->placeholder('—'),
                        TextEntry::make('description_ar')
                            ->label(__('filament.circle.description_ar'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('description_en')
                            ->label(__('filament.circle.description_en'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        ColorEntry::make('color')
                            ->label(__('filament.circle.color'))
                            ->placeholder('—'),
                        ColorEntry::make('bg_color')
                            ->label(__('filament.circle.bg_color'))
                            ->placeholder('—'),
                        TextEntry::make('members_count')
                            ->label(__('filament.circle.members_count')),
                        TextEntry::make('sort_order')
                            ->label(__('filament.circle.sort_order')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CircleMembersRelationManager::class,
            CircleStoriesRelationManager::class,
            CircleWinsRelationManager::class,
            CircleIcebreakersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCircles::route('/'),
            'view' => ViewCircle::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_circles');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_circles');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_circles');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_circles');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_circles');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_circles');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_circles');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
