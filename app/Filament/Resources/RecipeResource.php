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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ])
                    ->columns(1)
                    ->orderColumn('step_number')
                    ->addActionLabel('Stap toevoegen')
                    ->columnSpanFull(),
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
