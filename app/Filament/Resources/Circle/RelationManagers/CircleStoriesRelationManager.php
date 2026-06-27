<?php

namespace App\Filament\Resources\Circle\RelationManagers;

use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CircleStoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'stories';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.circle.stories_tab');
    }

    public function isReadOnly(): bool
    {
        return ! auth()->guard('admin')->user()->can('edit_circles');
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('filament.circle.author'))
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_anonymous')
                    ->label(__('filament.circle.anonymous'))
                    ->boolean(),

                TextColumn::make('body')
                    ->label(__('filament.circle.body'))
                    ->wrap()
                    ->limit(80),

                TextColumn::make('hearts_count')
                    ->label(__('filament.circle.hearts_count'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([])
            ->recordActions([
                DeleteAction::make()
                    ->label(__('filament.actions.delete'))
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_circles')),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
