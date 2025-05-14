<?php

namespace App\Filament\Resources\BookingResource\Pages;

use Filament\Resources\Pages\Page;
use App\Models\Booking;

class BookingSummaryReport extends Page
{

    protected static string $view = 'reports.booking_summary';  

    public $bookings;

    public function mount()
    {

        $this->bookings = Booking::with(['room.hotel'])->get();
    }
}
