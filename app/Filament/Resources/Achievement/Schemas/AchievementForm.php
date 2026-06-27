<?php

namespace App\Filament\Resources\Achievement\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AchievementForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label(__('filament.achievement.key'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('icon')
                    ->label(__('filament.achievement.icon'))
                    ->required()
                    ->maxLength(16),

                TextInput::make('name_ar')
                    ->label(__('filament.achievement.name_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('name_en')
                    ->label(__('filament.achievement.name_en'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('criterion_ar')
                    ->label(__('filament.achievement.criterion_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('criterion_en')
                    ->label(__('filament.achievement.criterion_en'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('sort_order')
                    ->label(__('filament.achievement.sort_order'))
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
