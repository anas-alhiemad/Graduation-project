<?php

namespace App\Models;

use App\Models\CourseSection;
use App\Models\CourseSectionWeekDay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeekDay extends Model
{
    use HasFactory;
        protected $fillable = ['name'];


        

        // public function coursePeriods()
        // {
        //     return $this->belongsToMany(CourseSection::class,'course_section_week_days')
        //                 ->withPivot('start_time', 'end_time');
        // }        
        // public function courseSectionWeekDay()
        // {
        //     return $this->hasMany(CourseSectionWeekDay::class,'dayId');
        // }

}
