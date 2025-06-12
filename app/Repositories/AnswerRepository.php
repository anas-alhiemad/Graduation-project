<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Student;
use App\Models\Trainer;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class AnswerRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Answer $model)
    {
        parent::__construct($model);
    }

    public function getQuestionAnswers($questionId)
    {
        return $this->model
            ->with(['user', 'likes.user'])
            ->where('question_id', $questionId)
            ->orderBy('is_accepted', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function createAnswer($data)
    {
        return $this->model->create($data);
    }

    public function updateAnswer($answerId, $data)
    {
        $answer = $this->getAnswer($answerId);
        if (!$answer) {
            return null;
        }
        
        $answer->update($data);
        return $answer->fresh(['user', 'likes.user']);
    }

    public function deleteAnswer($answerId)
    {
        $answer = $this->getAnswer($answerId);
        if (!$answer) {
            return false;
        }
        
        return $answer->delete();
    }

    public function getAnswer($answerId)
    {
        return $this->model->with(['user', 'likes.user'])
            ->where('id', $answerId)
            ->first();
    }

    public function getAnswerLikes($answerId)
    {
        $answer = $this->getAnswer($answerId);
        if (!$answer) {
            return null;
        }
        
        return $answer->likes()
            ->with('user')
            ->get()
            ->map(function ($like) {
                return [
                    'id' => $like->id,
                    'user' => [
                        'id' => $like->user->id,
                        'name' => $like->user->name,
                        'email' => $like->user->email,
                        'type' => $like->user instanceof Student ? 'student' : 'trainer'
                    ],
                    'created_at' => $like->created_at
                ];
            });
    }

    public function addLike($answerId, $userId, $userType)
    {
        $answer = $this->getAnswer($answerId);
        if (!$answer) {
            return null;
        }
        return $answer->likes()->create([
            'user_id' => $userId,
            'user_type' => $userType
        ]);
    }

    public function removeLike($answerId, $userId, $userType)
    {
        $answer = $this->getAnswer($answerId);
        if (!$answer) {
            return false;
        }

        $fullUserType = $userType === 'student' ? Student::class : Trainer::class;
        
        return $answer->likes()
            ->where('user_id', $userId)
            ->where('user_type', $fullUserType)
            ->delete();
    }

    public function incrementLikesCount($answerId)
    {
        return $this->model->where('id', $answerId)->increment('likes_count');
    }

    public function decrementLikesCount($answerId)
    {
        return $this->model->where('id', $answerId)->decrement('likes_count');
    }

    public function acceptAnswer($answerId)
    {
        return $this->model->where('id', $answerId)->update(['is_accepted' => true]);
    }

    public function unacceptAnswer($answerId)
    {
        return $this->model->where('id', $answerId)->update(['is_accepted' => false]);
    }
} 