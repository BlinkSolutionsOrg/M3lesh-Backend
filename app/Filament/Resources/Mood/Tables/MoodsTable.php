<?php

namespace App\Filament\Resources\Mood\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MoodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label(__('filament.mood.key'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('label_ar')
                    ->label(__('filament.mood.label'))
                    ->placeholder('—')
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->label_ar ?: $record->label_en)
                        : ($record->label_en ?: $record->label_ar))
                    ->searchable(['label_ar', 'label_en'])
                    ->sortable(),

                ColorColumn::make('color')
                    ->label(__('filament.mood.color')),

                TextColumn::make('sort_order')
                    ->label(__('filament.mood.sort_order'))
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->boolean(),
            ])
            ->recordActions([
                ViewAction::make()->iconButton()->color('gray'),
                EditAction::make()->iconButton()->color('primary')->slideOver()->hidden(fn ($record) => $record->trashed()),
                DeleteAction::make()->iconButton()->color('danger')->hidden(fn ($record) => $record->trashed()),
                RestoreAction::make()->iconButton()->color('success')->hidden(fn ($record) => ! $record->trashed()),
                ForceDeleteAction::make()->iconButton()->color('danger')->hidden(fn ($record) => ! $record->trashed()),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->placeholder(__('filament.filters.all'))
                    ->trueLabel(__('filament.filters.active'))
                    ->falseLabel(__('filament.filters.inactive')),
                TrashedFilter::make(),
            ])
            ->toolbarActions([])
            ->deferFilters(false)
            ->defaultSort('sort_order', 'asc');
    }
}
