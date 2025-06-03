<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'trainer_id',
        'course_section_id',
        'is_present',
        'date',
         'session_title',
    ];

    protected $casts = [
        'is_present' => 'boolean',
        'date' => 'date'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }
} 