<x-filament::page>
    <h2 class="text-xl font-bold mb-4">تقرير الحجوزات</h2>

<div class="flex justify-end mb-4">
    <form method="POST" action="{{ route('filament.pages.reports.bookings.download') }}">
        @csrf
       <x-filament::button type="submit" >
    تحميل التقرير PDF
</x-filament::button>

    </form>
</div>
    <table class="min-w-full border">
        <thead>
            <tr>
                <th class="border p-2">الفندق</th>
                <th class="border p-2">رقم الغرفة</th>
                <th class="border p-2">اسم النزيل</th>
                <th class="border p-2">تاريخ الوصول</th>
                <th class="border p-2">تاريخ المغادرة</th>
            </tr>
        </thead>
        <tbody>

            @foreach (\App\Models\Booking::latest()->take(10)->get() as $booking)
                <tr>
                    <td class="border p-2">{{ $booking->room->hotel->name }}</td>
                    <td class="border p-2">{{ $booking->room->room_number }}</td>
                    <td class="border p-2">{{ $booking->guest_name }}</td>
                    <td class="border p-2">{{ $booking->check_in }}</td>
                    <td class="border p-2">{{ $booking->check_out }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>
