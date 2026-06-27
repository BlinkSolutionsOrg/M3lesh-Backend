<?php

namespace App\Filament\Resources\WheelChallenge\Pages;

use App\Filament\Resources\WheelChallenge\WheelChallengeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWheelChallenges extends ListRecords
{
    protected static string $resource = WheelChallengeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => WheelChallengeResource::canCreate()),
        ];
    }
}
