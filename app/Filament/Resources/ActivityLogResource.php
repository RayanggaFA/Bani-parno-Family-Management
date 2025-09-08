<?php
// app/Filament/Resources/ActivityLogResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat Perubahan';
    protected static ?string $pluralLabel = 'Riwayat Perubahan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->label('Deskripsi')
                    ->disabled(),
                Forms\Components\TextInput::make('subject_type')
                    ->label('Tipe')
                    ->disabled(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Waktu')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Perubahan')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Tipe')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Tipe')
                    ->options([
                        'family' => 'Keluarga',
                        'member' => 'Anggota',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
            }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (auth()->guard('family')->check()) {
            $query->where('family_id', auth()->guard('family')->id());
        }
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
    }