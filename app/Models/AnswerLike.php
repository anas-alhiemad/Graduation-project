<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer_id',
        'user_id',
        'user_type' // 'student' or 'trainer'
    ];

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    public function user()
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($like) {
            if ($like->user_type === 'student') {
                $like->user_type = Student::class;
            } elseif ($like->user_type === 'trainer') {
                $like->user_type = Trainer::class;
            }
        });
    }
} 