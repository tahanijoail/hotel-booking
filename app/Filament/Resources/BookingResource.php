<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
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
use Carbon\Carbon;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'الحجوزات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('hotel_id')
                    ->label('اسم الفندق')
                    ->options(Hotel::all()->pluck('name', 'id'))
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (callable $set) {
                        $set('room_type', null);
                        $set('room_id', null);
                    }),

                Select::make('room_type')
                    ->label('نوع الغرفة')
                    ->options(function (callable $get) {
                        $hotelId = $get('hotel_id');
                        if ($hotelId) {
                            return Room::where('hotel_id', $hotelId)
                                ->distinct()
                                ->pluck('room_type', 'room_type');
                        }
                        return [];
                    })
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (callable $set) {
                        $set('room_id', null);
                    }),

                Select::make('room_id')
                    ->label('اختار الغرفة')
                    ->options(function (callable $get) {
                        $hotelId = $get('hotel_id');
                        $roomType = $get('room_type');
                        if ($hotelId && $roomType) {
                            return Room::where('hotel_id', $hotelId)
                                ->where('room_type', $roomType)
                                ->where('status', 'available')
                                ->pluck('room_number', 'id');
                        }
                        return [];
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn($set, $get) => self::calculateTotal($set, $get)),

                TextInput::make('guest_name')
                    ->label('اسم النزيل')
                    ->required(),

                Group::make()
                    ->schema([
                        TextInput::make('phone')->label('رقم الهاتف')->required(),
                        TextInput::make('email')->label('البريد الإلكتروني')->email()->required(),
                        TextInput::make('address')->label('العنوان')->nullable(),
                    ])
                    ->columns(2)
                    ->label('معلومات الاتصال')
                    ->statePath('contact_details') // هذا يربط الحقول مباشرة بمفتاح JSON
                    ->dehydrated(true),


                DatePicker::make('check_in')
                    ->label('تاريخ الوصول')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($set, $get) => self::calculateTotal($set, $get)),

                DatePicker::make('check_out')
                    ->label('تاريخ المغادرة')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($set, $get) => self::calculateTotal($set, $get)),

                Textarea::make('special_requests')
                    ->label('طلبات خاصة')
                    ->nullable(),

                TextInput::make('total_amount')
                    ->label('المبلغ الإجمالي')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->reactive()
                    ->afterStateHydrated(fn($set, $get) => self::calculateTotal($set, $get)),
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

    public static function calculateTotal($set, $get): void
    {
        $roomId = $get('room_id');
        $checkIn = $get('check_in');
        $checkOut = $get('check_out');

        if ($roomId && $checkIn && $checkOut) {
            $room = Room::find($roomId);
            if ($room) {
                $start = Carbon::parse($checkIn);
                $end = Carbon::parse($checkOut);
                $days = $end->diffInDays($start);
                $total = $room->price_per_night * max(1, $days); // لا يكون صفر حتى لو يوم واحد
                $set('total_amount', $total);
            } else {
                $set('total_amount', null);
            }
        } else {
            $set('total_amount', null);
        }
    }
}
