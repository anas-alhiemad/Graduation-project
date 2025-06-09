<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $fillable = ['question','quiz_id'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class,'quiz_id');
    }

    public function quizQuestionOption()
    {
        return $this->hasMany(QuizQuestionOption::class,'quiz_question_id');
    }
}
