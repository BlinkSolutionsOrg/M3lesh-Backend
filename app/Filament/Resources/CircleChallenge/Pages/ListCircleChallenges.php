<?php

namespace App\Filament\Resources\CircleChallenge\Pages;

use App\Filament\Resources\CircleChallenge\CircleChallengeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCircleChallenges extends ListRecords
{
    protected static string $resource = CircleChallengeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => CircleChallengeResource::canCreate()),
        ];
    }
}
