<?php

namespace App\Filament\Resources\Story\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'category', 'circle']))
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('filament.story.author'))
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_anonymous')
                    ->label(__('filament.story.anonymous'))
                    ->boolean(),

                TextColumn::make('category.label_ar')
                    ->label(__('filament.story.category'))
                    ->placeholder('—'),

                TextColumn::make('body')
                    ->label(__('filament.story.body'))
                    ->wrap()
                    ->limit(80)
                    ->searchable(),

                TextColumn::make('hearts_count')
                    ->label(__('filament.story.hearts_count'))
                    ->sortable(),

                TextColumn::make('comments_count')
                    ->label(__('filament.story.comments_count'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make()->iconButton()->color('gray'),
                DeleteAction::make()->iconButton()->color('danger')->hidden(fn ($record) => $record->trashed()),
                RestoreAction::make()->iconButton()->color('success')->hidden(fn ($record) => ! $record->trashed()),
                ForceDeleteAction::make()->iconButton()->color('danger')->hidden(fn ($record) => ! $record->trashed()),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->toolbarActions([])
            ->deferFilters(false)
            ->defaultSort('created_at', 'desc');
    }
}
