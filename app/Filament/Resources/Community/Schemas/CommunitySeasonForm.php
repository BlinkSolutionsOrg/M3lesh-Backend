<?php

namespace App\Filament\Resources\Community\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommunitySeasonForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                TextInput::make('name_ar')
                    ->label(__('filament.community_season.name_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('name_en')
                    ->label(__('filament.community_season.name_en'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('goal_leaves')
                    ->label(__('filament.community_season.goal_leaves'))
                    ->numeric()
                    ->default(100000),

                TextInput::make('leaves_count')
                    ->label(__('filament.community_season.leaves_count'))
                    ->numeric()
                    ->default(0),

                DateTimePicker::make('starts_at')
                    ->label(__('filament.community_season.starts_at')),

                DateTimePicker::make('ends_at')
                    ->label(__('filament.community_season.ends_at')),

                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
