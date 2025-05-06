<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Student;
use App\Models\Trainer;
use App\Models\WeekDay;
use App\Models\CourseSectionWeekDay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSection extends Model
{
    use HasFactory;
    protected $fillable = ['name','seatsOfNumber','startDate','endDate','courseId'];


    public function course()
    {
        return $this->belongsTo(Course::class,'courseId');
    }

    public function weekDays()
    {
        return $this->belongsToMany(WeekDay::class,'course_section_week_days') 
                    ->withPivot('start_time', 'end_time')->withTimestamps();
    }


    public function getFormattedWeekDaysAttribute()
    {
        return $this->weekDays()->get()->map(function ($day) {
            return [
                'name'       => $day->name,
                'start_time' => $day->pivot->start_time,
                'end_time'   => $day->pivot->end_time,
            ];
        });
    }


    public function students()
    {
        return $this->belongsToMany(Student::class, 'section_students')
                    ->withTimestamps();
    }
    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'section_trainers')
                    ->withTimestamps();
    }




}
