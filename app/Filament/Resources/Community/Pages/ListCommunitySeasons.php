<?php

namespace App\Filament\Resources\Community\Pages;

use App\Filament\Resources\Community\CommunitySeasonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCommunitySeasons extends ListRecords
{
    protected static string $resource = CommunitySeasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => CommunitySeasonResource::canCreate()),
        ];
    }
}
