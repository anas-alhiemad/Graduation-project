<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'user_type', // 'student' or 'trainer'
        'content',
        'is_accepted',
        'likes_count'
    ];

    protected $casts = [
        'is_accepted' => 'boolean'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function likes()
    {
        return $this->hasMany(AnswerLike::class);
    }

    public function user()
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($answer) {
            if ($answer->user_type === 'student') {
                $answer->user_type = Student::class;
            } elseif ($answer->user_type === 'trainer') {
                $answer->user_type = Trainer::class;
            }
        });
    }
} 