<?php

namespace App\Filament\Resources\RoomDecoration\Pages;

use App\Filament\Resources\RoomDecoration\RoomDecorationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoomDecorations extends ListRecords
{
    protected static string $resource = RoomDecorationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => RoomDecorationResource::canCreate()),
        ];
    }
}
