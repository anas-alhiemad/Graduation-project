<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
} 