<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_section_id',
        'rating',
        'comment'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }
} 