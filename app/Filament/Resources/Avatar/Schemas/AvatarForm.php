<?php

namespace App\Filament\Resources\Avatar\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class AvatarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('images')
                    ->label(__('filament.fields.image'))
                    ->helperText(__('filament.avatar.multi_upload_hint'))
                    // Avatars are uploaded as SVG. `->image()` enables the raster
                    // image editor and an `image/*` accept that rejects SVG. We
                    // also can't accept only `image/svg+xml`: Filament turns the
                    // accept list into a server-side `mimetypes` rule validated
                    // against PHP's finfo-detected MIME, which for SVG (XML text)
                    // is commonly reported as text/xml, application/xml or
                    // text/plain rather than image/svg+xml. Accept those variants
                    // so genuine SVGs always pass (the file picker still filters
                    // to these types for the trusted admin).
                    ->acceptedFileTypes([
                        'image/svg+xml',
                        'image/svg',
                        'text/xml',
                        'application/xml',
                        'text/plain',
                    ])
                    ->multiple()
                    ->minFiles(1)
                    ->reorderable()
                    ->appendFiles()
                    ->disk('public')
                    ->directory('avatars')
                    ->visibility('public')
                    ->imagePreviewHeight('160')
                    // ~50MB per file — effectively no limit for avatars (the
                    // raster-wrapped SVGs design tools export can be large). The
                    // PHP upload_max_filesize/post_max_size and the Livewire
                    // temporary-upload rule are raised to match (see docker
                    // php conf.d + config/livewire.php).
                    ->maxSize(51200)
                    ->columnSpanFull(),
            ]);
    }
}
