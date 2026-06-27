<?php

namespace App\Filament\Resources\WheelChallenge\Tables;

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

class WheelChallengesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('segment_emoji')
                    ->label(__('filament.wheel_challenge.segment_emoji')),

                TextColumn::make('title_ar')
                    ->label(__('filament.wheel_challenge.title'))
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->title_ar ?: $record->title_en)
                        : ($record->title_en ?: $record->title_ar))
                    ->searchable(['title_ar', 'title_en'])
                    ->sortable(),

                TextColumn::make('spins_count')
                    ->label(__('filament.wheel_challenge.spins_count'))
                    ->sortable(),

                TextColumn::make('responses_count')
                    ->label(__('filament.wheel_challenge.responses_count'))
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
            ->defaultSort('created_at', 'desc');
    }
}
