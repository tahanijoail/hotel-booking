<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;

class BookingReport extends Page
{
     protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.booking-report';
    protected static ?string $navigationLabel = 'تقرير الحجوزات';
    protected static ?string $title = 'تقرير الحجوزات';
    protected static ?string $slug = 'reports/bookings';
    protected static ?int $navigationSort = 10;

    public function download()
    {
        $bookings = Booking::with(['room.hotel'])->get();
        $pdf = Pdf::loadView('reports.booking_summary', compact('bookings'));

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'booking_summary.pdf'
        );
    }
}
