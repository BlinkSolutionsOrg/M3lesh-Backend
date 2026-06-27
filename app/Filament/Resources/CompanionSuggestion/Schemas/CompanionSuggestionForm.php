<?php

namespace App\Filament\Resources\CompanionSuggestion\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanionSuggestionForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                TextInput::make('text_ar')
                    ->label(__('filament.companion_suggestion.text_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('text_en')
                    ->label(__('filament.companion_suggestion.text_en'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('sort_order')
                    ->label(__('filament.companion_suggestion.sort_order'))
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
