<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billings extends Model
{
    use HasFactory;
    protected $table = 'billings';
    protected $fillable = ['student_id','lesson_id','teacher_id','amount','currency','is_paid','year','month'];

    public function student()
    {
        return $this->belongsTo(User::class,'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class,'teacher_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lessons::class,'lesson_id');
    }
}
