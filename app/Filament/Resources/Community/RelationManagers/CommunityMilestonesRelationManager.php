<?php

namespace App\Filament\Resources\Community\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CommunityMilestonesRelationManager extends RelationManager
{
    protected static string $relationship = 'milestones';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.community_season.milestones_tab');
    }

    public function isReadOnly(): bool
    {
        return ! auth()->guard('admin')->user()->can('edit_community_seasons');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('threshold')
                    ->label(__('filament.community_season.threshold'))
                    ->numeric()
                    ->required(),
                TextInput::make('label_ar')
                    ->label(__('filament.community_season.label_ar'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('label_en')
                    ->label(__('filament.community_season.label_en'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('reward_type')
                    ->label(__('filament.community_season.reward_type'))
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label(__('filament.community_season.sort_order'))
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->default(true),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('threshold')
                    ->label(__('filament.community_season.threshold'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('label_ar')
                    ->label(__('filament.community_season.label'))
                    ->formatStateUsing(fn ($record) => app()->getLocale() === 'ar'
                        ? ($record->label_ar ?: $record->label_en)
                        : ($record->label_en ?: $record->label_ar))
                    ->searchable(['label_ar', 'label_en'])
                    ->wrap()
                    ->limit(80),

                TextColumn::make('reward_type')
                    ->label(__('filament.community_season.reward_type'))
                    ->placeholder('—'),

                TextColumn::make('sort_order')
                    ->label(__('filament.community_season.sort_order'))
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('create_community_seasons')),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('edit_community_seasons')),
                DeleteAction::make()
                    ->visible(fn (): bool => auth()->guard('admin')->user()->can('delete_community_seasons')),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
