<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionStudent extends Model
{
    use HasFactory;
    protected $fillable = ['course_section_id','student_id','is_confirmed'];

        public function courseSection()
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
