<?php

namespace App\Filament\Resources\CircleChallenge\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CircleChallengeStepsRelationManager extends RelationManager
{
    protected static string $relationship = 'steps';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.circle_challenge.steps_tab');
    }

    public function isReadOnly(): bool
    {
        return ! auth()->guard('admin')->user()->can('edit_circle_challenges');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('text_ar')
                    ->label(__('filament.circle_challenge.step_text_ar'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('text_en')
                    ->label(__('filament.circle_challenge.step_text_en'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label(__('filament.circle.sort_order'))
                    ->numeric()
                    ->default(0),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('text_ar')
                    ->label(__('filament.circle_challenge.step_text'))
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->text_ar ?: $record->text_en)
                        : ($record->text_en ?: $record->text_ar))
                    ->wrap(),

                TextColumn::make('sort_order')
                    ->label(__('filament.circle.sort_order'))
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_circle_challenges')),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_circle_challenges')),
                DeleteAction::make()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_circle_challenges')),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
