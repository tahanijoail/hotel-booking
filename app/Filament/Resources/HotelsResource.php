<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelsResource\Pages;
use App\Filament\Resources\HotelsResource\RelationManagers;
use App\Models\Hotel;
use App\Models\Hotels;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HotelsResource extends Resource
{
    protected static ?string $model = Hotel::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'الفنادق';
    protected static ?string $navigationGroup = 'إدارة الفنادق';

    public static function form(Form $form): Form
    {
        return $form
             ->schema([
            TextInput::make('name')
                ->label('اسم الفندق')
                ->required(),
            TextInput::make('location')
                ->label('الموقع')
                ->required(),
            Textarea::make('description')
                ->label('الوصف'),
            TextInput::make('number_of_rooms')
                ->label('عدد الغرف')
                ->required()
                ->numeric(),

            \Filament\Forms\Components\Group::make()
                ->schema([
                    \Filament\Forms\Components\TextInput::make('contacts_data.phone')
                        ->label('رقم الهاتف')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('contacts_data.email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('contacts_data.website')
                        ->label('الموقع الإلكتروني')
                        ->url()
                        ->nullable(),
                ])
                ->columns(1)
                ->afterStateHydrated(function ($component, $state) {
                    if (is_string($state)) {
                        $decoded = json_decode($state, true);
                        $component->state([
                            'contacts_data' => $decoded ?? [],
                        ]);
                    }
                })
                ->dehydrated(true)
                ->dehydrateStateUsing(function ($state) {
                    return json_encode($state['contacts_data'] ?? []);
                })
                // ->columnSpan('full')
                ->label('تفاصيل الاتصال'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('اسم الفندق'),
                TextColumn::make('location')->label('الموقع'),
                TextColumn::make('number_of_rooms')->label('عدد الغرف'),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->date(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotels::route('/create'),
            'edit' => Pages\EditHotels::route('/{record}/edit'),
        ];
    }
}
