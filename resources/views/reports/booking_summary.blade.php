<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Summary Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Booking Summary Report</h2>

    <table>
        <thead>
            <tr>
                <th>Hotel Name</th>
                <th>Guest Name</th>
                <th>Contact Info</th>
                <th>Room Type</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Total Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->room->hotel->name }}</td>
                    <td>{{ $booking->guest_name }}</td>
                    <td>
                        Phone: {{ $booking->contact_details['phone'] ?? '' }}<br>
                        Email: {{ $booking->contact_details['email'] ?? '' }}
                    </td>
                    <td>{{ $booking->room->room_type }}</td>
                    <td>{{ $booking->check_in }}</td>
                    <td>{{ $booking->check_out }}</td>
                    <td>${{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
{{-- @extends('filament.pages.reports.booking-report.blade.php')

@section('content')
    <h2>تقرير الحجوزات</h2>

    <table>
        <thead>
            <tr>
                <th>اسم الفندق</th>
                <th>اسم النزيل</th>
                <th>معلومات الاتصال</th>
                <th>نوع الغرفة</th>
                <th>تاريخ الوصول</th>
                <th>تاريخ المغادرة</th>
                <th>المبلغ الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->room->hotel->name }}</td>  <!-- اسم الفندق -->
                    <td>{{ $booking->guest_name }}</td>  <!-- اسم النزيل -->
                    <td>
                        Phone: {{ $booking->contact_details['phone'] ?? '' }}<br>  <!-- رقم الهاتف -->
                        Email: {{ $booking->contact_details['email'] ?? '' }}  <!-- البريد الإلكتروني -->
                    </td>
                    <td>{{ $booking->room->room_type }}</td>  <!-- نوع الغرفة -->
                    <td>{{ $booking->check_in }}</td>  <!-- تاريخ الوصول -->
                    <td>{{ $booking->check_out }}</td>  <!-- تاريخ المغادرة -->
                    <td>${{ number_format($booking->total_amount, 2) }}</td>  <!-- المبلغ الإجمالي -->
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection --}}
