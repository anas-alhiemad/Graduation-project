<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ForumQuestion extends Model
{
    protected $fillable = [
        'section_id',
        'user_id',
        'user_type',
        'title',
        'content',
        'is_resolved'
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function user(): MorphTo
    {
        return $this->morphTo(null, 'user_type', 'user_id')
            ->withDefault([
                'name' => 'Unknown User',
                'email' => 'unknown@example.com'
            ]);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ForumAnswer::class, 'question_id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(ForumQuestion::class, 'forum_question_likes', 'question_id', 'user_id')
            ->withPivot('user_type')
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($question) {
            if ($question->user_type === 'trainer') {
                $question->user_type = Trainer::class;
            } elseif ($question->user_type === 'student') {
                $question->user_type = Student::class;
            }
        });
    }
   
} 