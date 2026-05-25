<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Filament\Resources\RecipeResource\RelationManagers\IngredientsRelationManager;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use OpenAI;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Recepten';

    protected static ?string $modelLabel = 'Recept';

    protected static ?string $pluralModelLabel = 'Recepten';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->label('Titel')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('created_by')
                    ->label('Maker')
                    ->options(User::pluck('username', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Beschrijving')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('badge')
                    ->label('Badge')
                    ->maxLength(100)
                    ->placeholder('bv. Student Favoriet'),
                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Afbeelding')
                    ->image()
                    ->disk('public')
                    ->directory('recepten')
                    ->maxSize(4096)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('prep_time_minutes')
                    ->label('Bereidingstijd (min)')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('calories_per_portion')
                    ->label('Calorieën per portie')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Select::make('afwas_score')
                    ->label('Afwasscore')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->nullable(),
                Forms\Components\CheckboxList::make('dietTags')
                    ->label('Categorieën')
                    ->relationship('dietTags', 'name')
                    ->columns(4)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('recipeIngredients')
                    ->label('Ingrediënten')
                    ->relationship('recipeIngredients')
                    ->schema([
                        Forms\Components\Select::make('ingredients_id')
                            ->label('Ingrediënt')
                            ->options(Ingredient::orderBy('canonical_name')->pluck('canonical_name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Hoeveelheid')
                            ->numeric()
                            ->default(1)
                            ->minValue(0),
                        Forms\Components\Select::make('unit')
                            ->label('Eenheid')
                            ->options([
                                'stuks'   => 'Stuks',
                                'g'       => 'G',
                                'kg'      => 'KG',
                                'ml'      => 'ML',
                                'l'       => 'L',
                                'blikken' => 'Blikken',
                                'zakjes'  => 'Zakjes',
                            ])
                            ->default('stuks')
                            ->required(),
                    ])
                    ->columns(2)
                    ->addActionLabel('Ingrediënt toevoegen')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('steps')
                    ->label('Stappen')
                    ->relationship('steps')
                    ->schema([
                        Forms\Components\Hidden::make('step_number'),
                        Forms\Components\Textarea::make('description')
                            ->label('Uitleg')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('video_timestamp')
                            ->label('Timestamp (bijv. 1:30 of 90)')
                            ->placeholder('mm:ss of seconden')
                            ->nullable(),
                    ])
                    ->columns(1)
                    ->orderColumn('step_number')
                    ->addActionLabel('Stap toevoegen')
                    ->columnSpanFull(),

                Forms\Components\Section::make('Voice Control Transcript')
                    ->description('Upload de audio van de video om een tijdgestempeld transcript te genereren voor voice search.')
                    ->schema([
                        Forms\Components\Placeholder::make('transcript_status')
                            ->label('Status')
                            ->content(fn (?Recipe $record) => $record?->transcript
                                ? count($record->transcript) . ' segmenten gegenereerd'
                                : 'Geen transcript'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generateTranscript')
                                ->label('Genereer Transcript')
                                ->icon(Heroicon::OutlinedMicrophone)
                                ->color('warning')
                                ->form([
                                    Forms\Components\FileUpload::make('audio_file')
                                        ->label('Audio of video bestand')
                                        ->acceptedFileTypes(['audio/*', 'video/*', 'audio/mpeg', 'audio/mp4', 'audio/wav', 'video/mp4'])
                                        ->maxSize(512000)
                                        ->required(),
                                ])
                                ->action(function (array $data, ?Recipe $record) {
                                    if (! $record) return;

                                    $path   = storage_path('app/public/' . $data['audio_file']);
                                    $client = OpenAI::client(config('services.openai.key'));

                                    $response = $client->audio()->transcribe([
                                        'model'           => 'whisper-1',
                                        'file'            => fopen($path, 'r'),
                                        'language'        => 'nl',
                                        'response_format' => 'verbose_json',
                                    ]);

                                    $segments = collect($response->segments)->map(fn ($s) => [
                                        'start' => round($s->start, 2),
                                        'end'   => round($s->end, 2),
                                        'text'  => trim($s->text),
                                    ])->values()->all();

                                    $record->update(['transcript' => $segments]);

                                    // Clean up uploaded file
                                    @unlink($path);

                                    Notification::make()
                                        ->title(count($segments) . ' transcript segmenten opgeslagen')
                                        ->success()
                                        ->send();
                                }),
                        ]),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Foto')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.username')
                    ->label('Maker')
                    ->sortable(),
                Tables\Columns\TextColumn::make('prep_time_minutes')
                    ->label('Bereidingstijd')
                    ->suffix(' min')
                    ->sortable(),
                Tables\Columns\TextColumn::make('avg_rating')
                    ->label('Beoordeling')
                    ->sortable(),
                Tables\Columns\TextColumn::make('review_count')
                    ->label('Reviews')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Aangemaakt')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            IngredientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit' => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }
}
