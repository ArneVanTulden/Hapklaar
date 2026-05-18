<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Reviews';

    protected static ?string $modelLabel = 'Review';

    protected static ?string $pluralModelLabel = 'Reviews';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('recipe_id')
                    ->label('Recept')
                    ->relationship('recipe', 'title')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Gebruiker')
                    ->relationship('user', 'username')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('rating')
                    ->label('Beoordeling')
                    ->options([
                        1 => '1 ster',
                        2 => '2 sterren',
                        3 => '3 sterren',
                        4 => '4 sterren',
                        5 => '5 sterren',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'In afwachting',
                        'approved' => 'Goedgekeurd',
                        'rejected' => 'Afgekeurd',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('Titel')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body')
                    ->label('Tekst')
                    ->rows(5)
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('recipe.title')
                    ->label('Recept')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Gebruiker')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state).str_repeat('☆', 5 - $state))
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state === 3 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('body')
                    ->label('Tekst')
                    ->limit(60)
                    ->tooltip(fn ($record): ?string => $record->body),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Geplaatst')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'In afwachting',
                        'approved' => 'Goedgekeurd',
                        'rejected' => 'Afgekeurd',
                    ]),
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Beoordeling')
                    ->options([
                        1 => '1 ster',
                        2 => '2 sterren',
                        3 => '3 sterren',
                        4 => '4 sterren',
                        5 => '5 sterren',
                    ]),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'edit'  => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
