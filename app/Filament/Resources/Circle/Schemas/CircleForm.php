<?php

namespace App\Filament\Resources\Circle\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CircleForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                TextInput::make('name_ar')
                    ->label(__('filament.circle.name_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('name_en')
                    ->label(__('filament.circle.name_en'))
                    ->required()
                    ->maxLength(255),

                Textarea::make('description_ar')
                    ->label(__('filament.circle.description_ar'))
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Textarea::make('description_en')
                    ->label(__('filament.circle.description_en'))
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                TextInput::make('emoji')
                    ->label(__('filament.circle.emoji'))
                    ->maxLength(16),

                TextInput::make('sort_order')
                    ->label(__('filament.circle.sort_order'))
                    ->numeric()
                    ->default(0),

                ColorPicker::make('color')
                    ->label(__('filament.circle.color')),

                ColorPicker::make('bg_color')
                    ->label(__('filament.circle.bg_color')),

                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
