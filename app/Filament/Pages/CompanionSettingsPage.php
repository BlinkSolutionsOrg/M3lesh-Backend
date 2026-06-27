<?php

namespace App\Filament\Pages;

use App\Models\Companion\CompanionSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Singleton settings management for «رفيق معلش» — the help banner, hotline number
 * and presence label shown in the companion chat.
 *
 * @property-read \Filament\Schemas\Schema $form
 */
class CompanionSettingsPage extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $slug = 'companion-settings';

    protected string $view = 'filament.pages.companion-settings-page';

    /**
     * @var array<string, mixed>
     */
    public array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('filament.companion_settings.nav');
    }

    public function getTitle(): string
    {
        return __('filament.companion_settings.nav');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->guard('admin')->user()->can('manage_companion_settings');
    }

    public static function canAccess(): bool
    {
        return auth()->guard('admin')->user()?->can('manage_companion_settings') ?? false;
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $settings = CompanionSetting::singleton();

        $this->form->fill([
            'help_banner_title_ar' => $settings->help_banner_title_ar,
            'help_banner_title_en' => $settings->help_banner_title_en,
            'help_banner_body_ar' => $settings->help_banner_body_ar,
            'help_banner_body_en' => $settings->help_banner_body_en,
            'hotline_number' => $settings->hotline_number,
            'presence_label_ar' => $settings->presence_label_ar,
            'presence_label_en' => $settings->presence_label_en,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make(__('filament.companion_settings.banner_section'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('help_banner_title_ar')
                            ->label(__('filament.companion_settings.help_banner_title_ar'))
                            ->maxLength(255),
                        TextInput::make('help_banner_title_en')
                            ->label(__('filament.companion_settings.help_banner_title_en'))
                            ->maxLength(255),
                        Textarea::make('help_banner_body_ar')
                            ->label(__('filament.companion_settings.help_banner_body_ar'))
                            ->rows(2)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                        Textarea::make('help_banner_body_en')
                            ->label(__('filament.companion_settings.help_banner_body_en'))
                            ->rows(2)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ]),
                Section::make(__('filament.companion_settings.presence_section'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('hotline_number')
                            ->label(__('filament.companion_settings.hotline_number'))
                            ->maxLength(64),
                        TextInput::make('presence_label_ar')
                            ->label(__('filament.companion_settings.presence_label_ar'))
                            ->maxLength(255),
                        TextInput::make('presence_label_en')
                            ->label(__('filament.companion_settings.presence_label_en'))
                            ->maxLength(255),
                    ]),
            ]);
    }

    public function save(): void
    {
        abort_unless(static::canAccess(), 403);

        $settings = CompanionSetting::singleton();
        $settings->update($this->form->getState());

        Notification::make()
            ->title(__('filament.companion_settings.saved'))
            ->success()
            ->send();
    }

    /**
     * @return array<int, Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament.companion_settings.save'))
                ->submit('save'),
        ];
    }
}
