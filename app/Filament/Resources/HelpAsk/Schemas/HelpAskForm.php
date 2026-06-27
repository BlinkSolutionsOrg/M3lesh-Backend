<?php

namespace App\Filament\Resources\HelpAsk\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HelpAskForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                Select::make('circle_id')
                    ->label(__('filament.help_ask.circle'))
                    ->relationship('circle', 'name_ar')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('status')
                    ->label(__('filament.help_ask.status'))
                    ->options([
                        'open' => __('filament.help_ask.status_open'),
                        'closed' => __('filament.help_ask.status_closed'),
                    ])
                    ->default('open')
                    ->required(),

                TextInput::make('title')
                    ->label(__('filament.help_ask.title'))
                    ->required()
                    ->maxLength(500)
                    ->columnSpanFull(),

                Textarea::make('body')
                    ->label(__('filament.help_ask.body'))
                    ->rows(4)
                    ->required()
                    ->maxLength(5000)
                    ->columnSpanFull(),

                Toggle::make('is_anonymous')
                    ->label(__('filament.help_ask.anonymous'))
                    ->default(false)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
