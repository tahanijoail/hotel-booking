<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تأكيد الحجز</title>
</head>
<body style="font-family: Arial, sans-serif; direction: rtl; text-align: right;">
    <h2>تأكيد الحجز</h2>

    <p>عزيزي {{ $guest_name }},</p>

    <p>نشكر لك الحجز في فندقنا. فيما يلي تفاصيل الحجز الخاصة بك:</p>

    <ul>
        <li><strong>تاريخ الوصول:</strong> {{ \Carbon\Carbon::parse($check_in)->format('Y-m-d') }}</li>
        <li><strong>تاريخ المغادرة:</strong> {{ \Carbon\Carbon::parse($check_out)->format('Y-m-d') }}</li>
        <li><strong>المبلغ الإجمالي:</strong> ${{ number_format($total_amount, 2) }}</li>
    </ul>

    <p>نتمنى لك إقامة سعيدة!</p>

    <p>مع تحيات,<br>إدارة الفندق</p>
</body>
</html>
