<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    use HasFactory;
    protected $table = 'lessons';
    protected $fillable = ['lesson_name','course_id','teacher_id','student_id','lesson_date','start_time','end_time','lesson_duration','user_level','duty_image','duty_text'];

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
