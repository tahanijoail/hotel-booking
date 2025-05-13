<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateBooking extends CreateRecord
{
    protected static string $resource = \App\Filament\Resources\BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = Booking::where('room_id', $data['room_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('check_in', [$data['check_in'], $data['check_out']])
                    ->orWhereBetween('check_out', [$data['check_in'], $data['check_out']]);
            })
            ->exists();

        if ($exists) {
            \Filament\Notifications\Notification::make()
                ->title('الغرفة محجوزة')
                ->body('لا يمكن الحجز لأن الغرفة محجوزة في هذه الفترة.')
                ->danger()
                ->send();

            throw new \Exception('الغرفة محجوزة');
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
