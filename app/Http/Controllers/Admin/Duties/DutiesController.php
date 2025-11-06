<?php

namespace App\Http\Controllers\Admin\Duties;

use App\Http\Controllers\Controller;
use App\Models\Lessons;
use App\Models\User;
use Illuminate\Http\Request;

class DutiesController extends Controller
{
    public function index(Request $request)
    {
        $query = Lessons::with(['teacher', 'student'])->whereNotNull('duty_text');

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('lesson_date', $request->date);
        } else {
            // Default to today if no date specified
            $query->whereDate('lesson_date', today());
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $duties = $query->orderBy('lesson_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $teachers = User::where('user_type', User::USER_TYPE['teacher'])->get();

        $selectedDate = $request->input('date', today()->format('Y-m-d'));
        $selectedTeacher = $request->input('teacher_id');

        return view('admin.duties.index', compact('duties', 'teachers', 'selectedDate', 'selectedTeacher'));
    }

    public function getStudents()
    {
        $students = User::where('user_type', User::USER_TYPE['student'])
            ->orderBy('user_name', 'asc')
            ->get();
        
        return response()->json($students);
    }

    public function studentDuties($student_id)
    {
        $student = User::findOrFail($student_id);
        
        $duties = Lessons::with(['teacher', 'student'])
            ->where('student_id', $student_id)
            ->whereNotNull('duty_text')
            ->orderBy('lesson_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('public.student-duties', compact('student', 'duties'));
    }

    public function studentDutyDetail($lesson_id)
    {
        $lesson = Lessons::with(['teacher', 'student'])->findOrFail($lesson_id);
        
        if (is_null($lesson->duty_text)) {
            abort(404, 'Duty not found');
        }
        
        return view('public.student-duty-detail', compact('lesson'));
    }
}

