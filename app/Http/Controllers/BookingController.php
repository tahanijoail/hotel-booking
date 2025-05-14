<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // دالة لحفظ الحجز
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required|string|max:255',
            'contact_details' => 'required|array',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        // حساب المبلغ الإجمالي على أساس الغرفة (أو يمكنك تخصيصها حسب احتياجك)
        $room = Room::find($validated['room_id']);
        $totalAmount = $room->price_per_night * (strtotime($validated['check_out']) - strtotime($validated['check_in'])) / 86400;

        // إنشاء الحجز
        $booking = Booking::create([
            'room_id' => $validated['room_id'],
            'guest_name' => $validated['guest_name'],
            'contact_details' => json_encode($validated['contact_details']),
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'special_requests' => $request->special_requests,
            'total_amount' => $totalAmount,
        ]);

        // إعادة التوجيه مع رسالة
        return redirect()->route('bookings.index')->with('success', 'تم الحجز بنجاح');
    }
}
