<?php

namespace App\Filament\Resources\StoryCategory\Pages;

use App\Filament\Resources\StoryCategory\StoryCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStoryCategories extends ListRecords
{
    protected static string $resource = StoryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->createAnother(false)
                ->visible(fn (): bool => StoryCategoryResource::canCreate()),
        ];
    }
}
