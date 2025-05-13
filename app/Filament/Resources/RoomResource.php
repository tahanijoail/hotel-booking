<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = 'الغرف';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('hotel_id')
                    ->label('الفندق')
                    ->relationship('hotel', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('room_number') // ← استخدم الاسم الصحيح من قاعدة البيانات
                    ->label('رقم الغرفة')
                    ->required(),

                Select::make('room_type') // ← الاسم الصحيح
                    ->label('نوع الغرفة')
                    ->options([
                        'single' => 'فردية',
                        'double' => 'مزدوجة',
                        'suite' => 'جناح',
                    ])
                    ->required(),

                TextInput::make('price_per_night') // ← الاسم الصحيح
                    ->label('السعر')
                    ->numeric()
                    ->required(),

                Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'available' => 'متاحة',
                        'booked' => 'محجوزة',
                        'under maintenance' => 'تحت الصيانة',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hotel.name')->label('الفندق'),
                TextColumn::make('room_number')->label('رقم الغرفة'),
                TextColumn::make('room_type')->label('النوع'),
                TextColumn::make('price_per_night')->label('السعر')->money('USD', true),
                TextColumn::make('status')->label('حالة الغرفة'),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->date(),
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
            'view' => Pages\ViewRoom::route('/{record}'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
