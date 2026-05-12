<?php

namespace App\Filament\Resources\RecipeResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class IngredientsRelationManager extends RelationManager
{
    protected static string $relationship = 'ingredients';

    protected static ?string $title = 'Ingrediënten';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('canonical_name')
            ->columns([
                Tables\Columns\TextColumn::make('canonical_name')
                    ->label('Ingrediënt')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.quantity')
                    ->label('Hoeveelheid'),
                Tables\Columns\TextColumn::make('pivot.unit')
                    ->label('Eenheid'),
                Tables\Columns\TextColumn::make('pivot.notes')
                    ->label('Notities'),
            ])
            ->headerActions([
                Actions\AttachAction::make()
                    ->label('Ingrediënt toevoegen')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name'])
                    ->form(fn (Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Hoeveelheid')
                            ->numeric(),
                        Forms\Components\TextInput::make('unit')
                            ->label('Eenheid')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('notes')
                            ->label('Notities')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('display_order')
                            ->label('Volgorde')
                            ->numeric()
                            ->default(0),
                    ]),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('quantity')
                            ->label('Hoeveelheid')
                            ->numeric(),
                        Forms\Components\TextInput::make('unit')
                            ->label('Eenheid')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('notes')
                            ->label('Notities')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('display_order')
                            ->label('Volgorde')
                            ->numeric(),
                    ]),
                Actions\DetachAction::make()
                    ->label('Verwijderen'),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DetachBulkAction::make()
                        ->label('Verwijderen'),
                ]),
            ]);
    }
}
