<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_section_id',
        'user_id',
        'user_type', // 'student' or 'trainer'
        'content',
        'likes_count'
    ];

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function likes()
    {
        return $this->hasMany(QuestionLike::class);
    }

    public function user()
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($question) {
            if ($question->user_type === 'student') {
                $question->user_type = Student::class;
            } elseif ($question->user_type === 'trainer') {
                $question->user_type = Trainer::class;
            }
        });
    }
} 