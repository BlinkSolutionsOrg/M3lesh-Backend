<?php

namespace App\Filament\Resources\Story;

use App\Filament\Resources\Story\Pages\ListStories;
use App\Filament\Resources\Story\Pages\ViewStory;
use App\Filament\Resources\Story\Tables\StoriesTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Story\Story;
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

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static ?string $recordTitleAttribute = 'body';

    protected static ?string $slug = 'stories';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.story');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.story_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.story');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_stories');
    }

    public static function table(Table $table): Table
    {
        return StoriesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.story.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_anonymous')
                            ->label(__('filament.story.anonymous'))
                            ->boolean(),
                        TextEntry::make('user.name')
                            ->label(__('filament.story.author'))
                            ->placeholder('—'),
                        TextEntry::make('category.label_ar')
                            ->label(__('filament.story.category'))
                            ->placeholder('—'),
                        TextEntry::make('circle.name_ar')
                            ->label(__('filament.story.circle'))
                            ->placeholder('—'),
                        TextEntry::make('title')
                            ->label(__('filament.story.title'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('body')
                            ->label(__('filament.story.body'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('hearts_count')
                            ->label(__('filament.story.hearts_count')),
                        TextEntry::make('comments_count')
                            ->label(__('filament.story.comments_count')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStories::route('/'),
            'view' => ViewStory::route('/{record}'),
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
        return false;
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
