<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSectionWeekDay extends Model
{
    use HasFactory;

    protected $fillable = ['start_time','end_time','course_section_id','day_id'];

}
