<?php

namespace App\Filament\Resources\Mood\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MoodForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label(__('filament.mood.key'))
                    ->required()
                    ->maxLength(64),

                Select::make('face_mood')
                    ->label(__('filament.mood.face_mood'))
                    ->options([
                        'great' => 'great',
                        'calm' => 'calm',
                        'sleepy' => 'sleepy',
                        'anxious' => 'anxious',
                        'sad' => 'sad',
                        'happy' => 'happy',
                    ]),

                TextInput::make('label_ar')
                    ->label(__('filament.mood.label_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('label_en')
                    ->label(__('filament.mood.label_en'))
                    ->required()
                    ->maxLength(255),

                ColorPicker::make('color')
                    ->label(__('filament.mood.color')),

                ColorPicker::make('bg_color')
                    ->label(__('filament.mood.bg_color')),

                TextInput::make('sort_order')
                    ->label(__('filament.mood.sort_order'))
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
