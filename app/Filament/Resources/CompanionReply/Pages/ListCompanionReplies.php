<?php

namespace App\Filament\Resources\CompanionReply\Pages;

use App\Filament\Resources\CompanionReply\CompanionReplyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanionReplies extends ListRecords
{
    protected static string $resource = CompanionReplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => CompanionReplyResource::canCreate()),
        ];
    }
}
