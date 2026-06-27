<?php

namespace App\Filament\Resources\Circle\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CirclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('emoji')
                    ->label(__('filament.circle.emoji'))
                    ->placeholder('—'),

                TextColumn::make('name_ar')
                    ->label(__('filament.circle.name'))
                    ->placeholder('—')
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->name_ar ?: $record->name_en)
                        : ($record->name_en ?: $record->name_ar))
                    ->searchable(['name_ar', 'name_en'])
                    ->sortable(),

                TextColumn::make('members_count')
                    ->label(__('filament.circle.members_count'))
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label(__('filament.circle.sort_order'))
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
