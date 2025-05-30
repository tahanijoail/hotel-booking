<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class CreateBooking extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = Booking::where('room_id', $data['room_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('check_in', [$data['check_in'], $data['check_out']])
                    ->orWhereBetween('check_out', [$data['check_in'], $data['check_out']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('check_in', '<=', $data['check_in'])
                              ->where('check_out', '>=', $data['check_out']);
                    });
            })
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('الغرفة محجوزة')
                ->body('لا يمكن الحجز لأن الغرفة محجوزة في هذه الفترة.')
                ->danger()
                ->persistent()
                ->send();

            $this->halt(); 
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $booking = $this->record;

        $email = json_decode($booking->contact_details, true)['email'] ?? null;

        if ($email) {
            Mail::to($email)->send(new BookingConfirmation($booking));
        }
    }
}
