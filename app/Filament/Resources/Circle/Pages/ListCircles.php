<?php

namespace App\Filament\Resources\Circle\Pages;

use App\Filament\Resources\Circle\CircleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCircles extends ListRecords
{
    protected static string $resource = CircleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => CircleResource::canCreate()),
        ];
    }
}
