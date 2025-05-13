<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'الحجوزات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('room_id')
                    ->relationship('room', 'room_number')
                    ->label('رقم الغرفة')
                    ->required()
                    ->searchable(),

                TextInput::make('guest_name')
                    ->label('اسم النزيل')
                    ->required(),

                Group::make()
                    ->schema([
                        TextInput::make('contact_details.phone')
                            ->label('رقم الهاتف')
                            ->required(),
                        TextInput::make('contact_details.email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required(),
                        TextInput::make('contact_details.address')
                            ->label('العنوان')
                            ->nullable(),
                    ])
                    ->columns(1)
                    ->afterStateHydrated(function ($component, $state) {
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            $component->state(['contact_details' => $decoded ?? []]);
                        }
                    })
                    ->dehydrated(true)
                    ->dehydrateStateUsing(function ($state) {
                        return json_encode($state['contact_details'] ?? []);
                    })
                    ->label('معلومات الاتصال'),

                DatePicker::make('check_in')
                    ->label('تاريخ الوصول')
                    ->required(),

                DatePicker::make('check_out')
                    ->label('تاريخ المغادرة')
                    ->required(),

                Textarea::make('special_requests')
                    ->label('طلبات خاصة')
                    ->nullable(),

                TextInput::make('total_amount')
                    ->label('المبلغ الإجمالي')
                    ->numeric()
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.room_number')->label('رقم الغرفة'),
                TextColumn::make('room.hotel.name')->label('الفندق'),
                TextColumn::make('room.room_type')->label('نوع الغرفة'),
                TextColumn::make('guest_name')->label('اسم النزيل'),
                TextColumn::make('check_in')->label('تاريخ الوصول')->date(),
                TextColumn::make('check_out')->label('تاريخ المغادرة')->date(),
                TextColumn::make('total_amount')->label('المبلغ الإجمالي')->money('USD', true),
            ])
            ->filters([
                SelectFilter::make('room.hotel_id')
                    ->label('الفندق')
                    ->options(Hotel::all()->pluck('name', 'id')),

                SelectFilter::make('room.room_type')
                    ->label('نوع الغرفة')
                    ->options([
                        'single' => 'فردية',
                        'double' => 'مزدوجة',
                        'suite' => 'جناح',
                    ]),

                SelectFilter::make('room.status')
                    ->label('حالة الغرفة')
                    ->options([
                        'available' => 'متاحة',
                        'unavailable' => 'غير متاحة',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
