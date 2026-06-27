<?php

namespace App\Filament\Resources\StoryCategory;

use App\Filament\Resources\StoryCategory\Pages\ListStoryCategories;
use App\Filament\Resources\StoryCategory\Pages\ViewStoryCategory;
use App\Filament\Resources\StoryCategory\Schemas\StoryCategoryForm;
use App\Filament\Resources\StoryCategory\Tables\StoryCategoriesTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Story\StoryCategory;
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

class StoryCategoryResource extends Resource
{
    protected static ?string $model = StoryCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'label_ar';

    protected static ?string $slug = 'story-categories';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.story_category');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.story_category_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.story_category');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_stories');
    }

    public static function form(Schema $schema): Schema
    {
        return StoryCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoryCategoriesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.story_category.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('key')
                            ->label(__('filament.story_category.key'))
                            ->placeholder('—'),
                        TextEntry::make('label_ar')
                            ->label(__('filament.story_category.label_ar'))
                            ->placeholder('—'),
                        TextEntry::make('label_en')
                            ->label(__('filament.story_category.label_en'))
                            ->placeholder('—'),
                        ColorEntry::make('color')
                            ->label(__('filament.story_category.color'))
                            ->placeholder('—'),
                        ColorEntry::make('bg_color')
                            ->label(__('filament.story_category.bg_color'))
                            ->placeholder('—'),
                        ColorEntry::make('border_color')
                            ->label(__('filament.story_category.border_color'))
                            ->placeholder('—'),
                        TextEntry::make('reaction_emoji')
                            ->label(__('filament.story_category.reaction_emoji'))
                            ->placeholder('—'),
                        TextEntry::make('reaction_label_ar')
                            ->label(__('filament.story_category.reaction_label_ar'))
                            ->placeholder('—'),
                        TextEntry::make('reaction_label_en')
                            ->label(__('filament.story_category.reaction_label_en'))
                            ->placeholder('—'),
                        TextEntry::make('sort_order')
                            ->label(__('filament.story_category.sort_order')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoryCategories::route('/'),
            'view' => ViewStoryCategory::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_stories');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_stories');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_stories');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_stories');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_stories');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_stories');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_stories');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
