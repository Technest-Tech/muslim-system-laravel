<?php

namespace App\Services;

use App\Models\Lessons;
use Carbon\Carbon;

class LessonRecurrenceService
{
    /**
     * Generate recurring lessons between start and end dates
     * 
     * @param array $data Lesson data (student_id, teacher_id, start_time, end_time, course_id, etc.)
     * @param string $startDate Start date (Y-m-d)
     * @param string $endDate End date (Y-m-d)
     * @param array $weekdays Selected weekdays (0=Sunday, 1=Monday, ..., 6=Saturday)
     * @return array Created lesson records
     */
    public function generateRecurringLessons(array $data, string $startDate, string $endDate, array $weekdays): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $lessons = [];
        
        // Calculate lesson duration in hours from start_time and end_time
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);
        // Calculate total minutes and convert to hours
        $totalMinutes = $endTime->diffInMinutes($startTime);
        $duration = round($totalMinutes / 60, 2);
        
        // Iterate through each day from start to end
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            // Check if current day is in the selected weekdays
            // Carbon: 0=Sunday, 1=Monday, ..., 6=Saturday
            $dayOfWeek = $currentDate->dayOfWeek;
            
            if (in_array($dayOfWeek, $weekdays)) {
                // Combine date with start_time for lesson_date
                $lessonDateTime = $currentDate->copy()
                    ->setTimeFromTimeString($data['start_time']);
                
                $lessonData = [
                    'student_id' => $data['student_id'],
                    'teacher_id' => $data['teacher_id'],
                    'lesson_date' => $lessonDateTime,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'lesson_duration' => $duration,
                    'course_id' => $data['course_id'] ?? null,
                    'lesson_name' => $data['lesson_name'] ?? 'Lesson',
                    'user_level' => $data['user_level'] ?? null,
                ];
                
                $lesson = Lessons::create($lessonData);
                $lessons[] = $lesson;
            }
            
            $currentDate->addDay();
        }
        
        return $lessons;
    }
}

