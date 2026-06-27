<?php

namespace App\Filament\Resources\WheelChallenge\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WheelChallengeForm
{
    /** The 8 wheel wedge emojis — must stay in sync with the app's kWheelSegments. */
    public const SEGMENT_EMOJIS = ['🔥', '❓', '😂', '🙈', '🎲', '📖', '🤍', '🤫'];

    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                Select::make('segment_emoji')
                    ->label(__('filament.wheel_challenge.segment_emoji'))
                    ->required()
                    ->options(array_combine(self::SEGMENT_EMOJIS, self::SEGMENT_EMOJIS)),

                TextInput::make('pill_label_ar')
                    ->label(__('filament.wheel_challenge.pill_label_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('pill_label_en')
                    ->label(__('filament.wheel_challenge.pill_label_en'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('title_ar')
                    ->label(__('filament.wheel_challenge.title_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('title_en')
                    ->label(__('filament.wheel_challenge.title_en'))
                    ->required()
                    ->maxLength(255),

                Textarea::make('compose_hint_ar')
                    ->label(__('filament.wheel_challenge.compose_hint_ar'))
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Textarea::make('compose_hint_en')
                    ->label(__('filament.wheel_challenge.compose_hint_en'))
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Textarea::make('room_banner_ar')
                    ->label(__('filament.wheel_challenge.room_banner_ar'))
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Textarea::make('room_banner_en')
                    ->label(__('filament.wheel_challenge.room_banner_en'))
                    ->rows(2)
                    ->maxLength(5000)
                    ->columnSpanFull(),

                ColorPicker::make('color')
                    ->label(__('filament.wheel_challenge.color')),

                ColorPicker::make('bg_color')
                    ->label(__('filament.wheel_challenge.bg_color')),

                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
