<?php

namespace App\Models;

use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'photo',
        'department_id'
    ];

    // Relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courseSection()
    {
        return $this->hasMany(CourseSection::class,'courseId');
    }

    // Relationship with students who saved this course
    public function savedByStudents()
    {
        return $this->belongsToMany(Student::class, 'saved_courses')
            ->withTimestamps();
    }
} 