<?php

namespace App\Filament\Resources\DailyCardTip\Pages;

use App\Filament\Resources\DailyCardTip\DailyCardTipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyCardTips extends ListRecords
{
    protected static string $resource = DailyCardTipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => DailyCardTipResource::canCreate()),
        ];
    }
}
