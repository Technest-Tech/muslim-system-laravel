<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الفواتير</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
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
        }
        .table th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }
        .table td {
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .footer {
            margin-top: 2rem;
            text-align: center;
        }
        .footer img {
            max-width: 150px;
            margin: 0.5rem;
        }
        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 150px;
            height: 120px;
        }
    </style>
</head>
<body>
<div class="container">
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('muslim.png'))) }}" style="width: 300px; height: 300px; "  class="logo">
    <h1 class="text-center my-4">تقرير الفواتير لشهر {{ $month }}</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th> اسم الطالب</th>
            <th>تاريخ الحصة</th>
            <th>مدة الحصة</th>
            <th>اسم المعلم </th>
        </tr>
        </thead>
        <tbody>
        @php
            $totalAmount = 0;
            $totalHours = 0;
            $student_id = 0;
        @endphp
        @foreach($billings as $billing)
            @php
                $totalAmount += $billing->amount;
                $totalHours += \App\Models\Lessons::find($billing->lesson_id)->lesson_duration ?? 0 ;
               $lesson = \App\Models\Lessons::find($billing->lesson_id);
                $student_id = $billing->student_id;
            @endphp
            <tr>
                <td style="text-align: center">{{ $billing->student->user_name }}</td>
                <td style="text-align: center">{{ \Carbon\Carbon::parse($lesson->lesson_date ?? '')->format('d-m-Y') }}</td>
<td style="text-align: center">
  {{ (\App\Models\Lessons::find($billing->lesson_id)->lesson_duration ?? 0) * 60 . ' ' . 'دقيقة' }}
</td>
<td style="text-align: center">{{ $billing->teacher->user_name ?? ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @php
        $totalWithTax = $totalAmount  + (($totalAmount*5)/100);
    @endphp
    <div class="footer">
        <p>Total Hours : {{ number_format($totalHours, 2) }} hours</p>
        </p>
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('ketm.png'))) }}" style="width: 150px; height: 150px; position: absolute; left: 0; margin: 20px 50px;">
    </div>
</div>
</body>
</html>
