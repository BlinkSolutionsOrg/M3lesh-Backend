<?php

namespace App\Filament\Resources\DailyCardTip\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DailyCardTipForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                Textarea::make('text_ar')
                    ->label(__('filament.daily_card_tip.text_ar'))
                    ->required()
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Textarea::make('text_en')
                    ->label(__('filament.daily_card_tip.text_en'))
                    ->required()
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                TextInput::make('emoji')
                    ->label(__('filament.daily_card_tip.emoji'))
                    ->maxLength(16),

                TextInput::make('sort_order')
                    ->label(__('filament.daily_card_tip.sort_order'))
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
