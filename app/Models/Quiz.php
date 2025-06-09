<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = ['title','course_section_id'];

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class,'course_section_id');
    }

    public function quizQuestion()
    {
        return $this->hasMany(QuizQuestion::class,'quiz_id');
    }


}
