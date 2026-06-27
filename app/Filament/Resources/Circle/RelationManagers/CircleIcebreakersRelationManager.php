<?php

namespace App\Filament\Resources\Circle\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CircleIcebreakersRelationManager extends RelationManager
{
    protected static string $relationship = 'icebreakers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.circle.icebreakers_tab');
    }

    public function isReadOnly(): bool
    {
        return ! auth()->guard('admin')->user()->can('edit_circles');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tag_ar')
                    ->label(__('filament.circle.icebreaker_tag_ar'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('tag_en')
                    ->label(__('filament.circle.icebreaker_tag_en'))
                    ->required()
                    ->maxLength(255),
                Textarea::make('question_ar')
                    ->label(__('filament.circle.icebreaker_question_ar'))
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('question_en')
                    ->label(__('filament.circle.icebreaker_question_en'))
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                ColorPicker::make('color')
                    ->label(__('filament.circle.color')),
                ColorPicker::make('bg_color')
                    ->label(__('filament.circle.bg_color')),
                TextInput::make('sort_order')
                    ->label(__('filament.circle.sort_order'))
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tag_ar')
                    ->label(__('filament.circle.icebreaker_tag'))
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->tag_ar ?: $record->tag_en)
                        : ($record->tag_en ?: $record->tag_ar))
                    ->searchable(['tag_ar', 'tag_en']),

                TextColumn::make('question_ar')
                    ->label(__('filament.circle.icebreaker_question'))
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->question_ar ?: $record->question_en)
                        : ($record->question_en ?: $record->question_ar))
                    ->wrap()
                    ->limit(80),

                TextColumn::make('sort_order')
                    ->label(__('filament.circle.sort_order'))
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('create_circles')),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_circles')),
                DeleteAction::make()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('delete_circles')),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
