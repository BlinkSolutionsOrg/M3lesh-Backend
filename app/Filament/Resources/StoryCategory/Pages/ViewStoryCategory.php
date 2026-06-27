<?php

namespace App\Filament\Resources\StoryCategory\Pages;

use App\Filament\Resources\StoryCategory\StoryCategoryResource;
use App\Models\Story\StoryCategory;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStoryCategory extends ViewRecord
{
    protected static string $resource = StoryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        /** @var StoryCategory $record */
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
