<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ForumAnswer extends Model
{
    protected $fillable = [
        'question_id',
        'user_id',
        'user_type',
        'content',
        'is_accepted'
    ];

    protected $casts = [
        'is_accepted' => 'boolean'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(ForumQuestion::class, 'question_id');
    }

    public function user(): MorphTo
    {
        return $this->morphTo(null, 'user_type', 'user_id')
            ->withDefault([
                'name' => 'Unknown User',
                'email' => 'unknown@example.com'
            ]);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(ForumAnswer::class, 'forum_answer_likes', 'answer_id', 'user_id')
            ->withPivot('user_type')
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($answer) {
            if ($answer->user_type === 'trainer') {
                $answer->user_type = Trainer::class;
            } elseif ($answer->user_type === 'student') {
                $answer->user_type = Student::class;
            }
        });
    }
     
} 