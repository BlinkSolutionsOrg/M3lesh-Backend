<?php

namespace App\Filament\Resources\CompanionSuggestion\Pages;

use App\Filament\Resources\CompanionSuggestion\CompanionSuggestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanionSuggestions extends ListRecords
{
    protected static string $resource = CompanionSuggestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => CompanionSuggestionResource::canCreate()),
        ];
    }
}
