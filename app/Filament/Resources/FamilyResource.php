<?php
// app/Filament/Resources/FamilyResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyResource\Pages;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Keluarga';
    protected static ?string $pluralLabel = 'Keluarga';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Keluarga')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                Forms\Components\TextInput::make('domicile')
                    ->label('Domisili')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Keluarga')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domicile')
                    ->label('Domisili')
                    ->searchable(),
                Tables\Columns\TextColumn::make('members_count')
                    ->label('Jumlah Anggota')
                    ->counts('members'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFamilies::route('/'),
            'create' => Pages\CreateFamily::route('/create'),
            'view' => Pages\ViewFamily::route('/{record}'),
            'edit' => Pages\EditFamily::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->guard('family')->check();
    }
}