<?php

namespace App\Filament\Resources\HelpAsk\Pages;

use App\Filament\Resources\HelpAsk\HelpAskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHelpAsks extends ListRecords
{
    protected static string $resource = HelpAskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => HelpAskResource::canCreate()),
        ];
    }
}
