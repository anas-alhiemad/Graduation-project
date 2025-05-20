<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'section_id',
        'trainer_id',
        'grade',
        'exam_date',
        'notes'
    ];

    protected $casts = [
        'exam_date' => 'datetime',
        'grade' => 'float'
    ];

    // Validation rules
    public static $rules = [
        'student_id' => 'required|exists:students,id',
        'section_id' => 'required|exists:course_sections,id',
        'trainer_id' => 'required|exists:trainers,id',
        'grade' => 'required|numeric|min:0|max:100',
        'exam_date' => 'required|date',
        'notes' => 'nullable|string|max:500'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
} 