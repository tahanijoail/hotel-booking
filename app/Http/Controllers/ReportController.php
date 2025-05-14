<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function bookingSummary()
    {
      $bookings = Booking::with(['room.hotel'])->get();

        $pdf = Pdf::loadView('reports.booking_summary', compact('bookings'));

        return $pdf->download('booking_summary.pdf');
    }
}
