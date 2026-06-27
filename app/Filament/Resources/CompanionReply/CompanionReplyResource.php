<?php

namespace App\Filament\Resources\CompanionReply;

use App\Filament\Resources\CompanionReply\Pages\ListCompanionReplies;
use App\Filament\Resources\CompanionReply\Pages\ViewCompanionReply;
use App\Filament\Resources\CompanionReply\Schemas\CompanionReplyForm;
use App\Filament\Resources\CompanionReply\Tables\CompanionRepliesTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Companion\CompanionReply;
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

class CompanionReplyResource extends Resource
{
    protected static ?string $model = CompanionReply::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $recordTitleAttribute = 'text_ar';

    protected static ?string $slug = 'companion-replies';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.companion_reply');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.companion_reply_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.companion_reply');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_companion_replies');
    }

    public static function form(Schema $schema): Schema
    {
        return CompanionReplyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanionRepliesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.companion_reply.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        IconEntry::make('is_greeting')
                            ->label(__('filament.companion_reply.is_greeting'))
                            ->boolean(),
                        TextEntry::make('weight')
                            ->label(__('filament.companion_reply.weight')),
                        TextEntry::make('text_ar')
                            ->label(__('filament.companion_reply.text_ar'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('text_en')
                            ->label(__('filament.companion_reply.text_en'))
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
            'index' => ListCompanionReplies::route('/'),
            'view' => ViewCompanionReply::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_companion_replies');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_companion_replies');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_companion_replies');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_companion_replies');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_companion_replies');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_companion_replies');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_companion_replies');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
