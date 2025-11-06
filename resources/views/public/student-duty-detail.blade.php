<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الواجب - {{ $lesson->student->user_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .duty-detail-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .duty-detail-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .duty-detail-body {
            padding: 30px;
            background-color: white;
        }
        .duty-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo-container {
            margin-bottom: 30px;
        }
        .back-link {
            margin-bottom: 20px;
        }
        .duty-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-right: 4px solid #007bff;
        }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <!-- Academy Logo -->
    <div class="text-center logo-container">
        <img src="{{ asset('muslim.png') }}" alt="Academy Logo" class="img-fluid" style="max-width: 350px;">
    </div>

    <!-- Back Link -->
    <div class="back-link">
        <a href="{{ route('student.duties', $lesson->student_id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة إلى قائمة الواجبات
        </a>
    </div>

    <!-- Student Name -->
    <div class="text-center mb-4">
        <h2>اسم الطالب: {{ $lesson->student->user_name }}</h2>
    </div>

    <!-- Duty Detail Card -->
    <div class="duty-detail-card">
        <div class="duty-detail-header">
            <h3 class="text-center mb-0">
                <i class="fas fa-book"></i> واجب الطالب بتاريخ 
                {{ \Carbon\Carbon::parse($lesson->lesson_date)->format('d-m-Y') }}
            </h3>
        </div>
        <div class="duty-detail-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong><i class="fas fa-book-open"></i> اسم الحصة:</strong> 
                    {{ $lesson->lesson_name ?? 'غير محدد' }}
                </div>
                <div class="col-md-6">
                    <strong><i class="fas fa-chalkboard-teacher"></i> المعلم:</strong> 
                    {{ $lesson->teacher->user_name ?? 'غير محدد' }}
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong><i class="fas fa-calendar"></i> تاريخ الحصة:</strong> 
                    {{ \Carbon\Carbon::parse($lesson->lesson_date)->format('Y-m-d') }}
                </div>
                @if($lesson->start_time && $lesson->end_time)
                <div class="col-md-6">
                    <strong><i class="fas fa-clock"></i> الوقت:</strong> 
                    {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
                </div>
                @endif
            </div>
            
            <div class="mb-4">
                <h5><i class="fas fa-file-alt"></i> نص الواجب:</h5>
                <div class="duty-text">
                    {{ $lesson->duty_text }}
                </div>
            </div>

            @if($lesson->duty_image)
            <div class="text-center">
                <h5 class="mb-3"><i class="fas fa-image"></i> صورة الواجب:</h5>
                <img src="{{ asset($lesson->duty_image) }}" alt="صورة الواجب" class="duty-image">
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

