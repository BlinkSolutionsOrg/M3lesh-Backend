<?php

namespace App\Filament\Resources\StoryCategory\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StoryCategoryForm
{
    public static function configure(Schema $schema, array $context = []): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label(__('filament.story_category.key'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('sort_order')
                    ->label(__('filament.story_category.sort_order'))
                    ->numeric()
                    ->default(0),

                TextInput::make('label_ar')
                    ->label(__('filament.story_category.label_ar'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('label_en')
                    ->label(__('filament.story_category.label_en'))
                    ->required()
                    ->maxLength(255),

                ColorPicker::make('color')
                    ->label(__('filament.story_category.color')),

                ColorPicker::make('bg_color')
                    ->label(__('filament.story_category.bg_color')),

                ColorPicker::make('border_color')
                    ->label(__('filament.story_category.border_color')),

                TextInput::make('reaction_emoji')
                    ->label(__('filament.story_category.reaction_emoji'))
                    ->maxLength(16),

                TextInput::make('reaction_label_ar')
                    ->label(__('filament.story_category.reaction_label_ar'))
                    ->maxLength(255),

                TextInput::make('reaction_label_en')
                    ->label(__('filament.story_category.reaction_label_en'))
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
