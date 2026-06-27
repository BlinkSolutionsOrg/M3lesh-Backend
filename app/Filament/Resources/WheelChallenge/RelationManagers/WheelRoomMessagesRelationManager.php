<?php

namespace App\Filament\Resources\WheelChallenge\RelationManagers;

use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WheelRoomMessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'roomMessages';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.wheel_challenge.room_messages_tab');
    }

    public function isReadOnly(): bool
    {
        return ! auth()->guard('admin')->user()->can('edit_wheel_challenges');
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('filament.wheel_challenge.author'))
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('body')
                    ->label(__('filament.wheel_challenge.body'))
                    ->wrap()
                    ->limit(80),

                TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([])
            ->recordActions([
                DeleteAction::make()
                    ->label(__('filament.actions.delete'))
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_wheel_challenges')),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
