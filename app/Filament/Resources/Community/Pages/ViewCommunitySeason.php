<?php

namespace App\Filament\Resources\Community\Pages;

use App\Filament\Resources\Community\CommunitySeasonResource;
use App\Models\Community\CommunitySeason;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCommunitySeason extends ViewRecord
{
    protected static string $resource = CommunitySeasonResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        /** @var CommunitySeason $record */
        $record = $this->getRecord();
        $resource = static::getResource();

        return [
            EditAction::make()
                ->slideOver()
                ->hidden(fn () => $record->trashed() || ! $resource::canEdit($record)),
            DeleteAction::make()
                ->successRedirectUrl($resource::getUrl('index'))
                ->hidden(fn () => $record->trashed() || ! $resource::canDelete($record)),
            RestoreAction::make()
                ->successRedirectUrl($resource::getUrl('index'))
                ->hidden(fn () => ! $record->trashed() || ! $resource::canRestore($record)),
            ForceDeleteAction::make()
                ->successRedirectUrl($resource::getUrl('index'))
                ->hidden(fn () => ! $record->trashed() || ! $resource::canForceDelete($record)),
        ];
    }
}
