@extends('layouts.index')

@section('content')
<div class="page-content-wrapper border">
    <!-- Title -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 mb-sm-0 text-end">تقويم الحصص</h1>
        </div>
    </div>

    <!-- Calendar -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lesson Detail Modal (Read-only) -->
<div class="modal fade" id="lessonModal" tabindex="-1" aria-labelledby="lessonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lessonModalLabel">تفاصيل الحصة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="lessonModalBody">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay d-none" id="loadingOverlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">جاري التحميل...</span>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<link rel="stylesheet" href="{{ asset('assets/css/calendar-custom.css') }}">
@endpush

@push('scripts')
<script>
    // Define routes and CSRF token for JavaScript
    window.calendarRoutes = {
        events: '{{ route("teacher.calendar.events") }}',
        show: '{{ route("teacher.calendar.show", ":id") }}'
    };
    window.csrfToken = '{{ csrf_token() }}';
</script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js' onload="console.log('FullCalendar loaded');"></script>
<script src="{{ asset('js/calendar-teacher.js') }}" defer></script>
@endpush
@endsection

