<?php

namespace App\Filament\Resources\Story\Pages;

use App\Filament\Resources\Story\StoryResource;
use Filament\Resources\Pages\ListRecords;

class ListStories extends ListRecords
{
    protected static string $resource = StoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
