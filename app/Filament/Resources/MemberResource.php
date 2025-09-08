<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('family_id')
                ->label('Keluarga')
                ->relationship('family', 'name')
                ->default(fn () => auth()->guard('family')->id())
                ->disabled(fn () => !auth()->guard('family')->user())
                ->required(),
            Forms\Components\TextInput::make('full_name')
                ->label('Nama Lengkap')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('profile_photo')
                ->label('Foto Profil')
                ->image()
                ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])
                ->maxSize(2048)
                ->directory('profile-photos'),
            Forms\Components\TextInput::make('birth_place')
                ->label('Tempat Lahir')
                ->required()
                ->maxLength(255),
            Forms\Components\DatePicker::make('birth_date')
                ->label('Tanggal Lahir')
                ->format('m/d/Y')
                ->displayFormat('m/d/Y')
                ->required(),
            Forms\Components\TextInput::make('occupation')
                ->label('Pekerjaan')
                ->maxLength(255),
            Forms\Components\TextInput::make('phone_number')
                ->label('No. Telepon')
                ->tel()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->maxLength(255),
            Forms\Components\Select::make('gender')
                ->label('Jenis Kelamin')
                ->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ])
                ->required(),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'Belum Menikah' => 'Belum Menikah',
                    'Sudah Menikah' => 'Sudah Menikah',
                    'Janda/Duda' => 'Janda/Duda',
                    'Memilih untuk tidak menjawab' => 'Memilih untuk tidak menjawab',
                ])
                ->required(),
            Forms\Components\Select::make('generation')
                ->label('Generasi')
                ->options([
                    1 => 'Generasi 1',
                    2 => 'Generasi 2', 
                    3 => 'Generasi 3',
                    4 => 'Generasi 4',
                    5 => 'Generasi 5',
                ])
                ->required(),
            Forms\Components\Select::make('parent_id')
                ->label('Orang Tua')
                ->relationship('parent', 'full_name')
                ->searchable()
                ->preload()
                ->nullable(),
            Forms\Components\TextInput::make('domicile_city')
                ->label('Kota Domisili')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('domicile_province')
                ->label('Provinsi Domisili')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('ktp_address')
                ->label('Alamat KTP')
                ->required()
                ->rows(3),
            Forms\Components\Textarea::make('current_address')
                ->label('Alamat Sekarang')
                ->required()
                ->rows(3),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }    
}
