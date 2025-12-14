<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('room_id')
                ->label('ID Kamar')
                ->nullable(),

            Forms\Components\FileUpload::make('img')
                ->label('Foto Kamar')
                ->image()
                ->directory('rooms')     // hanya FileUpload yang pakai ini
                ->disk('public')
                ->visibility('public')
                ->nullable(),

            Forms\Components\TextInput::make('location')
                ->label('Lokasi')
                ->required(),
            Forms\Components\TextInput::make('capacity')
                ->label('Kapasitas')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('price')
                ->label('Harga')
                ->numeric()
                ->prefix('Rp')
                ->required()
        ]);
    }

    public static function table(Table $table): Table
            {
        return $table->columns([
            Tables\Columns\ImageColumn::make('img')
                ->label(' ')
                ->disk('public')
                ->size(60),

            TextColumn::make('room_id')
                ->label('Kamar'),
                
            TextColumn::make('capacity')
                    ->label('Kapasitas'),
                                
            TextColumn::make('price')
                ->label('Harga')
                ->money('IDR'),
                
            TextColumn::make('location')
                ->label('Lokasi'),
                
        ])
            
            ->filters([
                //
            ])
            ->actions([
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
