<?php

namespace App\Filament\Pages;

use App\Support\Settings;
use BackedEnum;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Inhoud extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Inhoud';

    protected static ?string $title = 'Inhoud beheren';

    protected string $view = 'filament.pages.inhoud';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'marquee_text' => Settings::get('marquee_text'),
            'budget_hack_text' => Settings::get('budget_hack_text', 'Wissel kip voor kikkererwten en bespaar €2,40!'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Textarea::make('marquee_text')
                    ->label('Marquee banner tekst')
                    ->rows(4)
                    ->required(),
                Forms\Components\Textarea::make('budget_hack_text')
                    ->label('Budget hack tekst')
                    ->rows(2)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        Settings::set('marquee_text', $data['marquee_text']);
        Settings::set('budget_hack_text', $data['budget_hack_text']);

        Notification::make()
            ->title('Opgeslagen')
            ->success()
            ->send();
    }
}
