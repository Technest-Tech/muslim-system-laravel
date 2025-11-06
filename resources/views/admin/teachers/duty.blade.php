<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Duty</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <!-- Academy Logo -->
    <div class="text-center mb-4">
        <img src="{{asset('muslim.png')}}" alt="Academy Logo" class="img-fluid" style="max-width: 350px;">
    </div>
    <!-- Student Name -->
    <div class="text-center mb-4">
        <h2>اسم الطالب :
         {{$lesson->student->user_name}}
        </h2>
    </div>

    <!-- Student Duty -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-center"> واجب الطالب بتاريخ
                {{ \Carbon\Carbon::parse($lesson->lesson_date)->format('d-m-Y') }}
            </h3><br>
        </div>
        <div class="card-body">
            <h3 class="text-center">
                {{$lesson->duty_text}}
            </h3>
            <div class="d-flex justify-content-center align-items-center">
                <img src="{{asset($lesson->duty_image)}}">
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
