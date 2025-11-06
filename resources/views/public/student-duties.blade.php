<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>واجبات الطالب - {{ $student->user_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .duty-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 20px;
        }
        .duty-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .duty-card-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .duty-card-body {
            padding: 20px;
            background-color: white;
        }
        .logo-container {
            margin-bottom: 30px;
        }
        .student-name {
            color: #333;
            margin-bottom: 30px;
        }
        .no-duties {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <!-- Academy Logo -->
    <div class="text-center logo-container">
        <img src="{{ asset('muslim.png') }}" alt="Academy Logo" class="img-fluid" style="max-width: 350px;">
    </div>
    
    <!-- Student Name -->
    <div class="text-center student-name">
        <h2>واجبات الطالب: {{ $student->user_name }}</h2>
    </div>

    @if($duties->count() > 0)
        <div class="row">
            @foreach($duties as $duty)
                <div class="col-md-12">
                    <div class="duty-card">
                        <div class="duty-card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-0">
                                        <i class="fas fa-book"></i> 
                                        {{ $duty->lesson_name ?? 'واجب' }}
                                    </h5>
                                </div>
                                <div class="col-md-4 text-end">
                                    <small>
                                        <i class="fas fa-calendar"></i> 
                                        {{ \Carbon\Carbon::parse($duty->lesson_date)->format('d-m-Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="duty-card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-chalkboard-teacher"></i> المعلم:</strong> 
                                    {{ $duty->teacher->user_name ?? 'غير محدد' }}
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-clock"></i> التاريخ:</strong> 
                                    {{ \Carbon\Carbon::parse($duty->lesson_date)->format('Y-m-d') }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong><i class="fas fa-file-alt"></i> نص الواجب:</strong>
                                <p class="mt-2">{{ Str::limit($duty->duty_text, 150) }}</p>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('student.duty.detail', $duty->id) }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-eye"></i> عرض تفاصيل الواجب
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-duties">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <h4>لا توجد واجبات متاحة</h4>
            <p>لم يتم إضافة أي واجبات لهذا الطالب بعد.</p>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

