<?php

namespace App\Filament\Resources\DailyCardTip;

use App\Filament\Resources\DailyCardTip\Pages\ListDailyCardTips;
use App\Filament\Resources\DailyCardTip\Pages\ViewDailyCardTip;
use App\Filament\Resources\DailyCardTip\Schemas\DailyCardTipForm;
use App\Filament\Resources\DailyCardTip\Tables\DailyCardTipsTable;
use App\Filament\Support\AuditInfolistSection;
use App\Models\Space\DailyCardTip;
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

class DailyCardTipResource extends Resource
{
    protected static ?string $model = DailyCardTip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $recordTitleAttribute = 'text_ar';

    protected static ?string $slug = 'daily-card-tips';

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.daily_card_tip');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.daily_card_tip_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.daily_card_tip');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('view_daily_card_tips');
    }

    public static function form(Schema $schema): Schema
    {
        return DailyCardTipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyCardTipsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.daily_card_tip.section_info'))
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('is_active')
                            ->label(__('filament.fields.is_active'))
                            ->boolean(),
                        TextEntry::make('emoji')
                            ->label(__('filament.daily_card_tip.emoji'))
                            ->placeholder('—'),
                        TextEntry::make('text_ar')
                            ->label(__('filament.daily_card_tip.text_ar'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('text_en')
                            ->label(__('filament.daily_card_tip.text_en'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('sort_order')
                            ->label(__('filament.daily_card_tip.sort_order')),
                    ])
                    ->columns(2),
                AuditInfolistSection::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDailyCardTips::route('/'),
            'view' => ViewDailyCardTip::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('admin')->user()->can('view_daily_card_tips');
    }

    public static function canView($record): bool
    {
        return auth()->guard('admin')->user()->can('view_daily_card_tips');
    }

    public static function canCreate(): bool
    {
        return auth()->guard('admin')->user()->can('create_daily_card_tips');
    }

    public static function canEdit($record): bool
    {
        return auth()->guard('admin')->user()->can('edit_daily_card_tips');
    }

    public static function canDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('delete_daily_card_tips');
    }

    public static function canForceDelete($record): bool
    {
        return auth()->guard('admin')->user()->can('force_delete_daily_card_tips');
    }

    public static function canRestore($record): bool
    {
        return auth()->guard('admin')->user()->can('restore_daily_card_tips');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
