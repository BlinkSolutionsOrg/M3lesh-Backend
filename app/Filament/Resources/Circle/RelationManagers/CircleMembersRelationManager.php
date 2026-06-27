<?php

namespace App\Filament\Resources\Circle\RelationManagers;

use App\Models\Circle\CircleMember;
use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CircleMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.circle.members_tab');
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
                    ->label(__('filament.circle.member'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament.circle.joined_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([])
            ->recordActions([
                DeleteAction::make()
                    ->label(__('filament.actions.delete'))
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_circles'))
                    ->action(function (CircleMember $record): void {
                        $circle = $record->circle()->first();
                        if ($circle !== null && $circle->members_count > 0) {
                            $circle->decrement('members_count');
                        }
                        $record->delete();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
