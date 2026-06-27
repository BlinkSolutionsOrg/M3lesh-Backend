<?php

namespace App\Filament\Resources\CompanionSuggestion;

use App\Filament\Resources\CompanionSuggestion\Pages\ListCompanionSuggestions;
use App\Filament\Resources\CompanionSuggestion\Pages\ViewCompanionSuggestion;
use App\Filament\Resources\CompanionSuggestion\Schemas\CompanionSuggestionForm;
use App\Filament\Resources\CompanionSuggestion\Tables\CompanionSuggestionsTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Companion\CompanionSuggestion;
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

class CompanionSuggestionResource extends Resource
{
    protected static ?string $model = CompanionSuggestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $recordTitleAttribute = 'text_ar';

    protected static ?string $slug = 'companion-suggestions';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.companion_suggestion');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.companion_suggestion_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.companion_suggestion');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_companion_suggestions');
    }

    public static function form(Schema $schema): Schema
    {
        return CompanionSuggestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanionSuggestionsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.companion_suggestion.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('sort_order')
                            ->label(__('filament.companion_suggestion.sort_order')),
                        TextEntry::make('text_ar')
                            ->label(__('filament.companion_suggestion.text_ar'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('text_en')
                            ->label(__('filament.companion_suggestion.text_en'))
                            ->placeholder('—')
                            ->columnSpanFull(),
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
            'index' => ListCompanionSuggestions::route('/'),
            'view' => ViewCompanionSuggestion::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_companion_suggestions');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_companion_suggestions');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_companion_suggestions');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_companion_suggestions');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_companion_suggestions');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_companion_suggestions');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_companion_suggestions');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
