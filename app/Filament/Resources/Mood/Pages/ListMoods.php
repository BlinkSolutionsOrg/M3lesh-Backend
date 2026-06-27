<?php

namespace App\Filament\Resources\Mood\Pages;

use App\Filament\Resources\Mood\MoodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMoods extends ListRecords
{
    protected static string $resource = MoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => MoodResource::canCreate()),
        ];
    }
}
