<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelsResource\Pages;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput as TextInputComponent;

class HotelsResource extends Resource
{
    protected static ?string $model = Hotel::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'الفنادق';


    /**

     * @param  \Filament\Forms\Form  $form
     * @return \Filament\Forms\Form
     */
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

                Group::make()
                    ->schema([
                        TextInputComponent::make('contacts.phone')
                            ->label('رقم الهاتف')
                            ->required(),

                        TextInputComponent::make('contacts.email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required(),

                        TextInputComponent::make('contacts.website')
                            ->label('الموقع الإلكتروني')
                            ->url()
                            ->nullable(),
                    ])
                    ->columns(1)
                    ->afterStateHydrated(function ($component, $state) {
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            $component->state([
                                'contacts' => $decoded ?? [],
                            ]);
                        }
                    })
                    ->dehydrated(true)
                    ->dehydrateStateUsing(function ($state) {
                        return json_encode($state['contacts'] ?? []);
                    })
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
                TextColumn::make('description')->label('الوصف'),
                TextColumn::make('contacts')->label('التواصل'),
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
