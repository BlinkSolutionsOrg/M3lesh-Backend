<?php

namespace App\Filament\Resources\CircleChallenge\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CircleChallengeForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                Select::make('circle_id')
                    ->label(__('filament.circle_challenge.circle'))
                    ->relationship('circle', 'name_ar')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('title_ar')
                    ->label(__('filament.circle_challenge.title_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('title_en')
                    ->label(__('filament.circle_challenge.title_en'))
                    ->required()
                    ->maxLength(255),

                Textarea::make('description_ar')
                    ->label(__('filament.circle_challenge.description_ar'))
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Textarea::make('description_en')
                    ->label(__('filament.circle_challenge.description_en'))
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
