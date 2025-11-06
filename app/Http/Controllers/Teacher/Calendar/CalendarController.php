<?php

namespace App\Http\Controllers\Teacher\Calendar;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\TeacherStudents;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the read-only calendar view
     */
    public function index()
    {
        return view('teacher.calendar.index');
    }

    /**
     * Get events for calendar (JSON API) - filtered by teacher
     * Generate events dynamically from timetable entries and lessons
     */
    public function events(Request $request)
    {
        try {
            $events = [];

            // Get all lessons for this teacher (no date filtering)
            $lessons = \App\Models\Lessons::with(['student', 'teacher'])
                ->where('teacher_id', auth()->id())
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->get();

            // Add lesson events
            foreach ($lessons as $lesson) {
                $lessonDate = Carbon::parse($lesson->lesson_date);
                $startTimeParts = explode(':', $lesson->start_time);
                $endTimeParts = explode(':', $lesson->end_time);
                
                $startHour = (int)$startTimeParts[0];
                $startMinute = isset($startTimeParts[1]) ? (int)$startTimeParts[1] : 0;
                $endHour = (int)$endTimeParts[0];
                $endMinute = isset($endTimeParts[1]) ? (int)$endTimeParts[1] : 0;
                
                $startDateTime = $lessonDate->copy()
                    ->setTime($startHour, $startMinute, 0)
                    ->format('Y-m-d\TH:i:s');
                $endDateTime = $lessonDate->copy()
                    ->setTime($endHour, $endMinute, 0)
                    ->format('Y-m-d\TH:i:s');

                $startTimeMinutes = $startHour * 60 + $startMinute;
                $endTimeMinutes = $endHour * 60 + $endMinute;
                $totalMinutes = $endTimeMinutes - $startTimeMinutes;
                if ($totalMinutes < 0) {
                    $totalMinutes += 24 * 60;
                }
                $duration = round($totalMinutes / 60, 2);

                $events[] = [
                    'id' => 'l_' . $lesson->id,
                    'lesson_id' => $lesson->id,
                    'title' => $lesson->student->user_name ?? 'Student',
                    'start' => $startDateTime,
                    'end' => $endDateTime,
                    'student' => $lesson->student->user_name ?? 'N/A',
                    'teacher' => $lesson->teacher->user_name ?? 'N/A',
                    'lesson_name' => $lesson->lesson_name ?? 'Lesson',
                    'duration' => $duration,
                    'color' => $this->getEventColor($lesson->student_id),
                    'extendedProps' => [
                        'student_id' => $lesson->student_id,
                        'teacher_id' => $lesson->teacher_id,
                        'lesson_name' => $lesson->lesson_name,
                        'lesson_id' => $lesson->id,
                        'date' => $lessonDate->format('Y-m-d'),
                    ]
                ];
            }

            // Get ALL timetable entries for this teacher (removed TeacherStudents filter)
            $timetableEntries = Timetable::with(['student', 'teacher'])
                ->where('teacher_id', auth()->id())
                ->get();

            // Parse dates from FullCalendar for generating events within visible range
            $startInput = $request->input('start');
            $endInput = $request->input('end');
            
            // Extract date part only (YYYY-MM-DD) if it includes time or timezone
            if ($startInput) {
                // Remove timezone offset if present
                $startInput = preg_replace('/[+-]\d{2}:\d{2}$/', '', $startInput);
                if (strpos($startInput, 'T') !== false) {
                    $startInput = substr($startInput, 0, 10);
                }
                $start = Carbon::createFromFormat('Y-m-d', $startInput)->startOfDay();
            } else {
                $start = Carbon::now()->startOfMonth()->startOfDay();
            }
            
            if ($endInput) {
                // Remove timezone offset if present
                $endInput = preg_replace('/[+-]\d{2}:\d{2}$/', '', $endInput);
                if (strpos($endInput, 'T') !== false) {
                    $endInput = substr($endInput, 0, 10);
                }
                $end = Carbon::createFromFormat('Y-m-d', $endInput)->endOfDay();
            } else {
                $end = Carbon::now()->endOfMonth()->endOfDay();
            }

            // Generate events for each timetable entry
            foreach ($timetableEntries as $entry) {
                // Ensure start_date and end_date are strings in Y-m-d format
                $entryStartDate = is_string($entry->start_date) ? $entry->start_date : $entry->start_date->format('Y-m-d');
                $entryEndDate = is_string($entry->end_date) ? $entry->end_date : $entry->end_date->format('Y-m-d');
                
                $entryStart = Carbon::createFromFormat('Y-m-d', $entryStartDate)->startOfDay();
                $entryEnd = Carbon::createFromFormat('Y-m-d', $entryEndDate)->endOfDay();
                
                // Generate events for the visible date range (for calendar display)
                $currentDate = $entryStart->gt($start) ? $entryStart->copy() : $start->copy();
                $stopDate = $entryEnd->lt($end) ? $entryEnd->copy() : $end->copy();

                while ($currentDate->lte($stopDate)) {
                    // Check if current date matches the weekday
                    if ($currentDate->dayOfWeek == $entry->day) {
                        // Parse time strings properly (format: HH:MM:SS or HH:MM)
                        $startTimeStr = is_string($entry->start_time) ? $entry->start_time : $entry->start_time->format('H:i:s');
                        $endTimeStr = is_string($entry->end_time) ? $entry->end_time : $entry->end_time->format('H:i:s');
                        
                        $startTimeParts = explode(':', $startTimeStr);
                        $endTimeParts = explode(':', $endTimeStr);
                        
                        $startHour = (int)$startTimeParts[0];
                        $startMinute = isset($startTimeParts[1]) ? (int)$startTimeParts[1] : 0;
                        $endHour = (int)$endTimeParts[0];
                        $endMinute = isset($endTimeParts[1]) ? (int)$endTimeParts[1] : 0;
                        
                        // Calculate duration
                        $startTimeMinutes = $startHour * 60 + $startMinute;
                        $endTimeMinutes = $endHour * 60 + $endMinute;
                        $totalMinutes = $endTimeMinutes - $startTimeMinutes;
                        if ($totalMinutes < 0) {
                            $totalMinutes += 24 * 60; // Handle next day
                        }
                        $duration = round($totalMinutes / 60, 2);

                        // Create a fresh Carbon instance for the date and set time
                        $eventDate = Carbon::createFromDate(
                            $currentDate->year,
                            $currentDate->month,
                            $currentDate->day
                        );
                        
                        $startDateTime = $eventDate->copy()->setTime($startHour, $startMinute, 0)->format('Y-m-d\TH:i:s');
                        $endDateTime = $eventDate->copy()->setTime($endHour, $endMinute, 0)->format('Y-m-d\TH:i:s');

                        $events[] = [
                            'id' => 't_' . $entry->id . '_' . $currentDate->format('Y-m-d'),
                            'timetable_id' => $entry->id,
                            'title' => $entry->student->user_name ?? 'Student',
                            'start' => $startDateTime,
                            'end' => $endDateTime,
                            'student' => $entry->student->user_name ?? 'N/A',
                            'teacher' => $entry->teacher->user_name ?? 'N/A',
                            'lesson_name' => $entry->lesson_name ?? 'Lesson',
                            'duration' => $duration,
                            'color' => $this->getEventColor($entry->student_id),
                            'extendedProps' => [
                                'student_id' => $entry->student_id,
                                'teacher_id' => $entry->teacher_id,
                                'lesson_name' => $entry->lesson_name,
                                'timetable_id' => $entry->id,
                                'date' => $currentDate->format('Y-m-d'),
                            ]
                        ];
                    }
                    $currentDate->addDay();
                }
            }

            return response()->json($events);
        } catch (\Exception $e) {
            \Log::error('Teacher calendar events error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get single timetable entry or lesson details (read-only)
     */
    public function show($id)
    {
        // Handle lesson ID format: l_{lesson_id}
        if (strpos($id, 'l_') === 0) {
            $parts = explode('_', $id);
            if (count($parts) >= 2) {
                $lessonId = $parts[1];
                $lesson = \App\Models\Lessons::with(['student', 'teacher'])
                    ->where('teacher_id', auth()->id())
                    ->findOrFail($lessonId);
                
                return response()->json([
                    'success' => true,
                    'timetable' => $lesson // Reusing the same structure for compatibility
                ]);
            }
        }
        
        // Handle timetable ID format: t_{timetable_id}_{date}
        if (strpos($id, 't_') === 0) {
            $parts = explode('_', $id);
            if (count($parts) >= 2) {
                $timetableId = $parts[1];
                $timetable = Timetable::with(['student', 'teacher'])
                    ->where('teacher_id', auth()->id())
                    ->findOrFail($timetableId);
                
                return response()->json([
                    'success' => true,
                    'timetable' => $timetable
                ]);
            } else {
                abort(404);
            }
        } else {
            // Try as plain timetable ID
            $timetable = Timetable::with(['student', 'teacher'])
                ->where('teacher_id', auth()->id())
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'timetable' => $timetable
            ]);
        }
    }

    /**
     * Get color for event based on student ID
     */
    private function getEventColor($studentId)
    {
        $colors = [
            '#3b82f6', // blue
            '#10b981', // green
            '#f59e0b', // amber
            '#ef4444', // red
            '#8b5cf6', // purple
            '#ec4899', // pink
            '#06b6d4', // cyan
            '#84cc16', // lime
        ];

        return $colors[$studentId % count($colors)];
    }
}

