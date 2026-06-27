<?php

namespace App\Filament\Resources\HelpAsk;

use App\Filament\Resources\HelpAsk\Pages\ListHelpAsks;
use App\Filament\Resources\HelpAsk\Pages\ViewHelpAsk;
use App\Filament\Resources\HelpAsk\RelationManagers\HelpRepliesRelationManager;
use App\Filament\Resources\HelpAsk\Schemas\HelpAskForm;
use App\Filament\Resources\HelpAsk\Tables\HelpAsksTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Help\HelpAsk;
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

class HelpAskResource extends Resource
{
    protected static ?string $model = HelpAsk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $slug = 'help-asks';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.help_ask');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.help_ask_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.help_ask');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_help_asks');
    }

    public static function form(Schema $schema): Schema
    {
        return HelpAskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HelpAsksTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.help_ask.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('filament.help_ask.title'))
                            ->columnSpanFull(),
                        TextEntry::make('body')
                            ->label(__('filament.help_ask.body'))
                            ->columnSpanFull(),
                        TextEntry::make('user.name')
                            ->label(__('filament.help_ask.author'))
                            ->placeholder('—'),
                        IconEntry::make('is_anonymous')
                            ->label(__('filament.help_ask.anonymous'))
                            ->boolean(),
                        TextEntry::make('circle.name_ar')
                            ->label(__('filament.help_ask.circle'))
                            ->placeholder('—'),
                        TextEntry::make('status')
                            ->label(__('filament.help_ask.status'))
                            ->formatStateUsing(fn ($state) => $state === 'closed'
                                ? __('filament.help_ask.status_closed')
                                : __('filament.help_ask.status_open')),
                        TextEntry::make('replies_count')
                            ->label(__('filament.help_ask.replies_count')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            HelpRepliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHelpAsks::route('/'),
            'view' => ViewHelpAsk::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_help_asks');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_help_asks');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_help_asks');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_help_asks');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_help_asks');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_help_asks');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_help_asks');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
