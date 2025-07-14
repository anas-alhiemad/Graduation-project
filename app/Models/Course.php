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

   
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courseSection()
    {
        return $this->hasMany(CourseSection::class,'courseId');
    }

    public function savedByStudents()
    {
        return $this->belongsToMany(Student::class, 'saved_courses')
            ->withTimestamps();
    }
} 