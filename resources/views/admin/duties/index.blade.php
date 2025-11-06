@extends('layouts.index')

@section('content')
    <style>
        .fancy-card {
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 16px;
            margin: 16px 0;
            background-color: #fff;
        }
        .duty-link {
            font-size: 12px;
        }
    </style>
    <!-- Page main content START -->
    <div class="page-content-wrapper border">

        <!-- Title -->
        <div class="row">
            <div class="col-12 text-end">
                <h1 class="h3 mb-2 mb-sm-0">الواجبات</h1>
            </div>
        </div><br>

        <!-- Students Links Section -->
        <div class="card bg-transparent border mb-3">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">روابط الطلاب</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentsModal">
                    <i class="fas fa-users"></i> عرض روابط جميع الطلاب
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="card bg-transparent border mb-3">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">التصفية</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('duties.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="date" class="form-label">التاريخ</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $selectedDate }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="teacher_id" class="form-label">المعلم</label>
                        <select class="form-select" id="teacher_id" name="teacher_id">
                            <option value="">كل المعلمين</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $selectedTeacher == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">بحث</button>
                        <a href="{{ route('duties.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card START -->
        <div class="card bg-transparent border">
            <!-- Card header START -->
            <div class="card-header bg-light border-bottom">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">عدد الواجبات: {{ $duties->count() }}</h5>
                    </div>
                </div>
            </div>

            @if($duties->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">الطالب</th>
                            <th scope="col" class="text-center">المعلم</th>
                            <th scope="col" class="text-center">اسم الحصة</th>
                            <th scope="col" class="text-center">تاريخ الحصة</th>
                            <th scope="col" class="text-center">نص الواجب</th>
                            <th scope="col" class="text-center">رابط الواجب</th>
                            <th scope="col" class="text-center">إرسال واتساب</th>
                            <th scope="col" class="text-center">نسخ الرابط</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($duties as $duty)
                            <tr>
                                <td class="text-center">{{ $duty->student->user_name ?? 'غير محدد' }}</td>
                                <td class="text-center">{{ $duty->teacher->user_name ?? 'غير محدد' }}</td>
                                <td class="text-center">{{ $duty->lesson_name ?? 'غير محدد' }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($duty->lesson_date)->format('Y-m-d') }}
                                </td>
                                <td class="text-center">
                                    <span title="{{ $duty->duty_text }}">
                                        {{ Str::limit($duty->duty_text, 30) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('student-duty', $duty->id) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-external-link-alt"></i> عرض الواجب
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if($duty->student && $duty->student->whatsapp_number)
                                        <a 
                                            target="_blank" 
                                            href="https://wa.me/{{ $duty->student->whatsapp_number }}?text={{ urlencode('الواجب الخاص بكم في الرابط بالأسفل ' . route('student-duty', $duty->id)) }}" 
                                            class="btn btn-sm btn-success"
                                        >
                                            <i class="fab fa-whatsapp"></i> واتساب
                                        </a>
                                    @else
                                        <span class="text-muted">لا يوجد رقم واتساب</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="input-group" style="width: 300px; margin: 0 auto;">
                                        <input 
                                            type="text" 
                                            class="form-control form-control-sm duty-link" 
                                            id="dutyLink{{ $duty->id }}" 
                                            value="{{ route('student-duty', $duty->id) }}" 
                                            readonly
                                        >
                                        <button 
                                            class="btn btn-sm btn-outline-secondary" 
                                            type="button" 
                                            onclick="copyToClipboard('dutyLink{{ $duty->id }}')"
                                            title="نسخ الرابط"
                                        >
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="card-body text-center">
                    <p class="text-muted">لا توجد واجبات في هذا التاريخ</p>
                </div>
            @endif
        </div>
        <!-- Card END -->
    </div>
    <!-- Page main content END -->

    <!-- Students Modal -->
    <div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentsModalLabel">روابط جميع الطلاب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="studentsLoading" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                    </div>
                    <div id="studentsList" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">اسم الطالب</th>
                                        <th scope="col" class="text-center">رابط الواجبات</th>
                                        <th scope="col" class="text-center">نسخ الرابط</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="studentsError" style="display: none;" class="alert alert-danger">
                        <p class="mb-0">حدث خطأ أثناء تحميل الطلاب</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
            
            // Show success message
            var button = copyText.nextElementSibling;
            var originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.add('btn-success');
            button.classList.remove('btn-outline-secondary');
            
            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }

        // Load students when modal is opened
        document.getElementById('studentsModal').addEventListener('show.bs.modal', function () {
            loadStudents();
        });

        function loadStudents() {
            var loading = document.getElementById('studentsLoading');
            var list = document.getElementById('studentsList');
            var error = document.getElementById('studentsError');
            var tableBody = document.getElementById('studentsTableBody');

            loading.style.display = 'block';
            list.style.display = 'none';
            error.style.display = 'none';
            tableBody.innerHTML = '';

            fetch('{{ route("duties.getStudents") }}')
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    
                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="3" class="text-center">لا يوجد طلاب</td></tr>';
                    } else {
                        data.forEach(function(student) {
                            var row = document.createElement('tr');
                            var studentDutiesUrl = '{{ url("/") }}/student-duties/' + student.id;
                            
                            row.innerHTML = 
                                '<td class="text-center">' + student.user_name + '</td>' +
                                '<td class="text-center">' +
                                    '<a href="' + studentDutiesUrl + '" target="_blank" class="btn btn-sm btn-info">' +
                                        '<i class="fas fa-external-link-alt"></i> عرض الواجبات' +
                                    '</a>' +
                                '</td>' +
                                '<td class="text-center">' +
                                    '<div class="input-group" style="width: 300px; margin: 0 auto;">' +
                                        '<input type="text" class="form-control form-control-sm duty-link" ' +
                                            'id="studentLink' + student.id + '" value="' + studentDutiesUrl + '" readonly>' +
                                        '<button class="btn btn-sm btn-outline-secondary" type="button" ' +
                                            'onclick="copyToClipboard(\'studentLink' + student.id + '\')" title="نسخ الرابط">' +
                                            '<i class="fas fa-copy"></i>' +
                                        '</button>' +
                                    '</div>' +
                                '</td>';
                            
                            tableBody.appendChild(row);
                        });
                    }
                    
                    list.style.display = 'block';
                })
                .catch(err => {
                    loading.style.display = 'none';
                    error.style.display = 'block';
                    console.error('Error loading students:', err);
                });
        }
    </script>
@endsection

