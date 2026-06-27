<?php

namespace App\Filament\Resources\RoomDecoration\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RoomDecorationForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label(__('filament.room_decoration.key'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('required_level')
                    ->label(__('filament.room_decoration.required_level'))
                    ->numeric()
                    ->minValue(1)
                    ->default(1),

                TextInput::make('title_ar')
                    ->label(__('filament.room_decoration.title_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('title_en')
                    ->label(__('filament.room_decoration.title_en'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('sort_order')
                    ->label(__('filament.room_decoration.sort_order'))
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
