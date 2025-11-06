// Wait for FullCalendar to be loaded
function initCalendarTeacher() {
    if (typeof FullCalendar === 'undefined') {
        setTimeout(initCalendarTeacher, 100);
        return;
    }

    let calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        setTimeout(initCalendarTeacher, 100);
        return;
    }

    let calendar;

    // Initialize FullCalendar (read-only)
    calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'ar',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        editable: false,
        selectable: false,
        events: function(fetchInfo, successCallback, failureCallback) {
            showLoading();
            fetch(window.calendarRoutes.events + '?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr)
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    successCallback(data);
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            loadLessonDetails(info.event.id);
        },
        eventDisplay: 'block',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },
        eventDidMount: function(info) {
            // Add custom styling and enhance event display
            info.el.style.cursor = 'pointer';
            
            // Create a more informative title with time
            const startTime = info.event.start ? new Date(info.event.start).toLocaleTimeString('ar-EG', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            }) : '';
            const endTime = info.event.end ? new Date(info.event.end).toLocaleTimeString('ar-EG', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            }) : '';
            
            // Update title to include time
            const studentName = info.event.extendedProps.student || info.event.title;
            const timeRange = startTime && endTime ? ` (${startTime} - ${endTime})` : '';
            
            // Set title attribute for tooltip
            info.el.setAttribute('title', `${studentName} - ${info.event.extendedProps.teacher || 'N/A'}${timeRange}`);
            
            // Enhance event content display
            const titleElement = info.el.querySelector('.fc-event-title');
            if (titleElement) {
                titleElement.style.fontSize = '0.85rem';
                titleElement.style.fontWeight = '700';
                titleElement.style.lineHeight = '1.3';
            }
            
            // Ensure event has minimum height based on view
            const isTimeGrid = info.view.type.includes('timeGrid');
            if (isTimeGrid) {
                info.el.style.minHeight = '80px';
                info.el.style.display = 'flex';
                info.el.style.flexDirection = 'column';
                info.el.style.alignItems = 'flex-start';
                info.el.style.justifyContent = 'flex-start';
                info.el.style.padding = '8px 10px';
                info.el.style.overflow = 'visible';
                
                // Ensure proper spacing for child elements
                const titleContainer = info.el.querySelector('.fc-event-title-container');
                if (titleContainer) {
                    titleContainer.style.display = 'flex';
                    titleContainer.style.flexDirection = 'column';
                    titleContainer.style.gap = '4px';
                    titleContainer.style.marginBottom = '4px';
                    titleContainer.style.width = '100%';
                }
                
                const titleElement = info.el.querySelector('.fc-event-title');
                if (titleElement) {
                    titleElement.style.marginBottom = '4px';
                    titleElement.style.marginTop = '0';
                    titleElement.style.display = 'block';
                    titleElement.style.lineHeight = '1.4';
                    titleElement.style.height = 'auto';
                    titleElement.style.minHeight = 'auto';
                    titleElement.style.padding = '0';
                }
                
                const teacherElement = info.el.querySelector('.fc-event-teacher');
                if (teacherElement) {
                    teacherElement.style.marginTop = '0';
                    teacherElement.style.marginBottom = '4px';
                    teacherElement.style.display = 'block';
                    teacherElement.style.lineHeight = '1.3';
                    teacherElement.style.height = 'auto';
                    teacherElement.style.minHeight = 'auto';
                    teacherElement.style.padding = '0';
                }
                
                const timeElement = info.el.querySelector('.fc-event-time');
                if (timeElement) {
                    timeElement.style.marginTop = '4px';
                    timeElement.style.display = 'block';
                    timeElement.style.lineHeight = '1.3';
                    timeElement.style.height = 'auto';
                    timeElement.style.padding = '0';
                }
            } else {
                info.el.style.minHeight = '40px';
                info.el.style.padding = '8px 12px';
            }
        },
        eventContent: function(arg) {
            // Custom event content rendering
            const studentName = arg.event.extendedProps.student || arg.event.title;
            const teacherName = arg.event.extendedProps.teacher || '';
            
            // Get start and end times
            let startTime = '';
            let endTime = '';
            if (arg.event.start) {
                const start = new Date(arg.event.start);
                startTime = start.toLocaleTimeString('ar-EG', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true 
                });
            }
            if (arg.event.end) {
                const end = new Date(arg.event.end);
                endTime = end.toLocaleTimeString('ar-EG', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true 
                });
            }
            
            const timeRange = startTime && endTime ? `${startTime} - ${endTime}` : (startTime || arg.timeText || '');
            
            let html = '<div class="fc-event-main-frame">';
            html += '<div class="fc-event-title-container">';
            html += '<div class="fc-event-title">' + studentName + '</div>';
            if (teacherName) {
                html += '<div class="fc-event-teacher">' + teacherName + '</div>';
            }
            html += '</div>';
            if (timeRange) {
                html += '<div class="fc-event-time">' + timeRange + '</div>';
            }
            html += '</div>';
            
            return { html: html };
        }
    });

    calendar.render();

    // Load lesson details (read-only)
    function loadLessonDetails(eventId) {
        showLoading();
        fetch(window.calendarRoutes.show.replace(':id', eventId), {
            credentials: 'same-origin'
        })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success && data.timetable) {
                    displayLessonDetails(data.timetable, eventId);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'لم يتم العثور على تفاصيل الحصة',
                        confirmButtonText: 'حسناً'
                    });
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading lesson:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء تحميل تفاصيل الحصة',
                    confirmButtonText: 'حسناً'
                });
            });
    }

    // Display lesson details in modal (read-only)
    function displayLessonDetails(data, eventId) {
        const modalBody = document.getElementById('lessonModalBody');
        const modal = new bootstrap.Modal(document.getElementById('lessonModal'));
        
        // Check if it's a lesson (has lesson_date) or timetable entry (has start_date/end_date)
        const isLesson = data.lesson_date !== undefined && data.lesson_date !== null;
        
        // Get day name for timetable entries
        const dayNames = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        const dayName = data.day !== undefined ? dayNames[data.day] || 'N/A' : '';
        
        // Extract date from event ID if it's a timetable entry with specific date
        let eventDate = null;
        if (eventId && eventId.startsWith('t_')) {
            const parts = eventId.split('_');
            if (parts.length >= 3) {
                eventDate = parts[2]; // Extract date from event ID format: t_{timetable_id}_{date}
            }
        }
        
        let html = '<div class="row">';
        
        // Student
        html += `<div class="col-md-6 mb-3">
            <strong>الطالب:</strong> ${data.student?.user_name || 'N/A'}
        </div>`;
        
        // Teacher
        html += `<div class="col-md-6 mb-3">
            <strong>المعلم:</strong> ${data.teacher?.user_name || 'N/A'}
        </div>`;
        
        // Lesson name
        html += `<div class="col-md-6 mb-3">
            <strong>اسم الحصة:</strong> ${data.lesson_name || 'N/A'}
        </div>`;
        
        // For timetable entries, show day
        if (!isLesson && dayName) {
            html += `<div class="col-md-6 mb-3">
                <strong>اليوم:</strong> ${dayName}
            </div>`;
        }
        
        // For lessons, show course
        if (isLesson && data.course) {
            html += `<div class="col-md-6 mb-3">
                <strong>المادة:</strong> ${data.course?.course_name || 'N/A'}
            </div>`;
        }
        
        // Date handling
        if (isLesson) {
            // Lesson has specific date
            html += `<div class="col-md-6 mb-3">
                <strong>تاريخ الحصة:</strong> ${new Date(data.lesson_date).toLocaleDateString('ar-EG')}
            </div>`;
        } else {
            // Timetable entry - show date range or specific event date
            if (eventDate) {
                html += `<div class="col-md-6 mb-3">
                    <strong>تاريخ الحصة:</strong> ${new Date(eventDate).toLocaleDateString('ar-EG')}
                </div>`;
            }
            html += `<div class="col-md-6 mb-3">
                <strong>تاريخ البداية:</strong> ${new Date(data.start_date).toLocaleDateString('ar-EG')}
            </div>`;
            html += `<div class="col-md-6 mb-3">
                <strong>تاريخ النهاية:</strong> ${new Date(data.end_date).toLocaleDateString('ar-EG')}
            </div>`;
        }
        
        // Start time
        html += `<div class="col-md-6 mb-3">
            <strong>وقت البداية:</strong> ${formatTimeTo12Hour(data.start_time)}
        </div>`;
        
        // End time
        html += `<div class="col-md-6 mb-3">
            <strong>وقت النهاية:</strong> ${formatTimeTo12Hour(data.end_time)}
        </div>`;
        
        // Duration - calculate if not available
        let duration = data.lesson_duration || data.duration;
        if (!duration && data.start_time && data.end_time) {
            const startParts = data.start_time.split(':');
            const endParts = data.end_time.split(':');
            const startMinutes = parseInt(startParts[0]) * 60 + parseInt(startParts[1] || 0);
            const endMinutes = parseInt(endParts[0]) * 60 + parseInt(endParts[1] || 0);
            let totalMinutes = endMinutes - startMinutes;
            if (totalMinutes < 0) totalMinutes += 24 * 60;
            duration = (totalMinutes / 60).toFixed(2);
        }
        
        if (duration) {
            html += `<div class="col-md-6 mb-3">
                <strong>المدة:</strong> ${duration} ساعة
            </div>`;
        }
        
        html += '</div>';
        
        modalBody.innerHTML = html;
        modal.show();
    }

    // Show/hide loading
    // Helper function to format time from 24-hour to 12-hour format
    function formatTimeTo12Hour(timeString) {
        if (!timeString) return '';
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'م' : 'ص';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    }

    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('d-none');
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('d-none');
    }
}

// Start initialization when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCalendarTeacher);
} else {
    initCalendarTeacher();
}

