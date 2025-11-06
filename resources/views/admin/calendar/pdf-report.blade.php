<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير تقويم الحصص</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            position: relative;
        }
        .text-center {
            text-align: center;
        }
        .my-4 {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            vertical-align: middle;
            text-align: center;
        }
        .table th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table td {
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .header-info {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
        .header-info p {
            margin: 0.5rem 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="text-center my-4">
        <h1>تقرير تقويم الحصص</h1>
    </div>
    
    <div class="header-info">
        <p><strong>من تاريخ:</strong> {{ is_string($start) ? $start : $start->format('Y-m-d') }}</p>
        <p><strong>إلى تاريخ:</strong> {{ is_string($end) ? $end : $end->format('Y-m-d') }}</p>
        @if($studentName)
            <p><strong>الطالب:</strong> {{ $studentName }}</p>
        @endif
        @if($teacherName)
            <p><strong>المعلم:</strong> {{ $teacherName }}</p>
        @endif
        <p><strong>عدد الحصص:</strong> {{ count($events) }}</p>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>التاريخ</th>
            <th>اليوم</th>
            <th>وقت البداية</th>
            <th>وقت النهاية</th>
            <th>الطالب</th>
            <th>المعلم</th>
            <th>اسم الحصة</th>
            <th>المدة (ساعة)</th>
        </tr>
        </thead>
        <tbody>
        @php
            $dayNames = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        @endphp
        @foreach($events as $event)
            @php
                $eventDate = \Carbon\Carbon::parse($event->start_date);
                $dayName = $dayNames[$eventDate->dayOfWeek] ?? 'N/A';
                
                // Format time to 12-hour
                $startTimeStr = is_string($event->start_time) ? $event->start_time : $event->start_time->format('H:i:s');
                $endTimeStr = is_string($event->end_time) ? $event->end_time : $event->end_time->format('H:i:s');
                
                $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $startTimeStr);
                $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $endTimeStr);
                
                $startHour = (int)$startTime->format('H');
                $endHour = (int)$endTime->format('H');
                
                $startTime12 = ($startHour % 12 ?: 12) . ':' . $startTime->format('i') . ' ' . ($startHour >= 12 ? 'م' : 'ص');
                $endTime12 = ($endHour % 12 ?: 12) . ':' . $endTime->format('i') . ' ' . ($endHour >= 12 ? 'م' : 'ص');
                
                // Calculate duration
                $duration = $endTime->diffInMinutes($startTime) / 60;
                if ($duration < 0) {
                    $duration += 24;
                }
            @endphp
            <tr>
                <td>{{ $eventDate->format('Y-m-d') }}</td>
                <td>{{ $dayName }}</td>
                <td>{{ $startTime12 }}</td>
                <td>{{ $endTime12 }}</td>
                <td>{{ $event->student->user_name ?? 'N/A' }}</td>
                <td>{{ $event->teacher->user_name ?? 'N/A' }}</td>
                <td>{{ $event->lesson_name ?? 'Lesson' }}</td>
                <td>{{ number_format($duration, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    @if(count($events) == 0)
        <div class="text-center my-4">
            <p>لا توجد حصص في هذا النطاق</p>
        </div>
    @endif
</div>
</body>
</html>

