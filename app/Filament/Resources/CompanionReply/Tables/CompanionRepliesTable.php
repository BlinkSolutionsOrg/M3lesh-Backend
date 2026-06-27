<?php

namespace App\Filament\Resources\CompanionReply\Tables;

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

class CompanionRepliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('text_ar')
                    ->label(__('filament.companion_reply.text'))
                    ->placeholder('—')
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->text_ar ?: $record->text_en)
                        : ($record->text_en ?: $record->text_ar))
                    ->searchable(['text_ar', 'text_en'])
                    ->wrap()
                    ->limit(80),

                IconColumn::make('is_greeting')
                    ->label(__('filament.companion_reply.is_greeting'))
                    ->boolean(),

                TextColumn::make('weight')
                    ->label(__('filament.companion_reply.weight'))
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
                TernaryFilter::make('is_greeting')
                    ->label(__('filament.companion_reply.is_greeting'))
                    ->placeholder(__('filament.filters.all')),
                TernaryFilter::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->placeholder(__('filament.filters.all'))
                    ->trueLabel(__('filament.filters.active'))
                    ->falseLabel(__('filament.filters.inactive')),
                TrashedFilter::make(),
            ])
            ->toolbarActions([])
            ->deferFilters(false)
            ->defaultSort('updated_at', 'desc');
    }
}
