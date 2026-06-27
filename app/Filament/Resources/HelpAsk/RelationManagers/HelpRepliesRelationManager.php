<?php

namespace App\Filament\Resources\HelpAsk\RelationManagers;

use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HelpRepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'replies';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.help_ask.replies_tab');
    }

    public function isReadOnly(): bool
    {
        return ! auth()->guard('admin')->user()->can('edit_help_asks');
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('filament.help_ask.author'))
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_anonymous')
                    ->label(__('filament.help_ask.anonymous'))
                    ->boolean(),

                TextColumn::make('type')
                    ->label(__('filament.help_ask.reply_type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'experience'
                        ? __('filament.help_ask.type_experience')
                        : __('filament.help_ask.type_advice')),

                TextColumn::make('body')
                    ->label(__('filament.help_ask.body'))
                    ->wrap()
                    ->limit(80),

                TextColumn::make('votes_count')
                    ->label(__('filament.help_ask.votes_count'))
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
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_help_asks')),
            ])
            ->defaultSort('votes_count', 'desc');
    }
}
