<?php

namespace App\Filament\Resources\HelpAsk\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class HelpAsksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'circle']))
            ->columns([
                TextColumn::make('title')
                    ->label(__('filament.help_ask.title'))
                    ->wrap()
                    ->limit(60)
                    ->searchable(['title', 'body']),

                TextColumn::make('user.name')
                    ->label(__('filament.help_ask.author'))
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_anonymous')
                    ->label(__('filament.help_ask.anonymous'))
                    ->boolean(),

                TextColumn::make('circle.name_ar')
                    ->label(__('filament.help_ask.circle'))
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->circle?->name_ar ?: $record->circle?->name_en)
                        : ($record->circle?->name_en ?: $record->circle?->name_ar))
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label(__('filament.help_ask.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'closed'
                        ? __('filament.help_ask.status_closed')
                        : __('filament.help_ask.status_open'))
                    ->color(fn ($state) => $state === 'closed' ? 'gray' : 'success'),

                TextColumn::make('replies_count')
                    ->label(__('filament.help_ask.replies_count'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make()->iconButton()->color('gray'),
                EditAction::make()->iconButton()->color('primary')->slideOver()->hidden(fn ($record) => $record->trashed()),
                DeleteAction::make()->iconButton()->color('danger')->hidden(fn ($record) => $record->trashed()),
                RestoreAction::make()->iconButton()->color('success')->hidden(fn ($record) => ! $record->trashed()),
                ForceDeleteAction::make()->iconButton()->color('danger')->hidden(fn ($record) => ! $record->trashed()),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.help_ask.status'))
                    ->options([
                        'open' => __('filament.help_ask.status_open'),
                        'closed' => __('filament.help_ask.status_closed'),
                    ]),
                TrashedFilter::make(),
            ])
            ->toolbarActions([])
            ->deferFilters(false)
            ->defaultSort('created_at', 'desc');
    }
}
