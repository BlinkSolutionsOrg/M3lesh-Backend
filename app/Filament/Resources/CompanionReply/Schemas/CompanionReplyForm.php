<?php

namespace App\Filament\Resources\CompanionReply\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanionReplyForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                Textarea::make('text_ar')
                    ->label(__('filament.companion_reply.text_ar'))
                    ->required()
                    ->rows(2)
                    ->maxLength(2000)
                    ->columnSpanFull(),

                Textarea::make('text_en')
                    ->label(__('filament.companion_reply.text_en'))
                    ->required()
                    ->rows(2)
                    ->maxLength(2000)
                    ->columnSpanFull(),

                TextInput::make('weight')
                    ->label(__('filament.companion_reply.weight'))
                    ->numeric()
                    ->minValue(1)
                    ->default(1),

                Toggle::make('is_greeting')
                    ->label(__('filament.companion_reply.is_greeting'))
                    ->default(false),

                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
