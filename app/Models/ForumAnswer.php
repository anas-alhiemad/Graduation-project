<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ForumAnswer extends Model
{
    protected $fillable = [
        'question_id',
        'user_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(ForumAnswer::class, 'forum_answer_likes', 'answer_id', 'user_id')
            ->withPivot('user_type')
            ->withTimestamps();
    }
} 