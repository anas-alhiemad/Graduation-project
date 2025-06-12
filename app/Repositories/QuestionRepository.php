<?php

namespace App\Repositories;

use App\Models\Question;
use App\Models\Student;
use App\Models\Trainer;
use App\Repositories\BaseRepository;
use App\Interfaces\RepositoryInterface;

class QuestionRepository extends BaseRepository implements RepositoryInterface
{
    public function __construct(Question $model)
    {
        parent::__construct($model);
    }

    public function getSectionQuestions($sectionId, $perPage = 10)
    {
        return $this->model
            ->with([
                'user',
                'answers' => function ($query) {
                    $query->with(['user', 'likes.user'])
                        ->orderBy('is_accepted', 'desc')
                        ->orderBy('created_at', 'asc');
                },
                'likes.user'
            ])
            ->where('course_section_id', $sectionId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getQuestionAnswers($questionId, $perPage = 10)
    {
        $question = $this->getQuestion($questionId);
        if (!$question) {
            return null;
        }

        return $question->answers()
            ->with(['user', 'likes.user'])
            ->orderBy('is_accepted', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    public function createQuestion($data)
    {
        return $this->model->create($data);
    }

    public function updateQuestion($questionId, $data)
    {
        $question = $this->getQuestion($questionId);
        if (!$question) {
            return null;
        }
        
        $question->update($data);
        return $question->fresh(['user', 'answers.user', 'likes.user']);
    }

    public function deleteQuestion($questionId)
    {
        $question = $this->getQuestion($questionId);
        if (!$question) {
            return false;
        }
        
        return $question->delete();
    }

    public function getQuestion($questionId)
    {
        return $this->model->with(['user', 'answers.user', 'likes.user'])
            ->where('id', $questionId)
            ->first();
    }

    public function addLike($questionId, $userId, $userType)
    {
        $question = $this->getQuestion($questionId);
        if (!$question) {
            return null;
        }
        return $question->likes()->create([
            'user_id' => $userId,
            'user_type' => $userType
        ]);
    }

    public function removeLike($questionId, $userId, $userType)
    {
        $question = $this->getQuestion($questionId);
        if (!$question) {
            return false;
        }

        $fullUserType = $userType === 'student' ? Student::class : Trainer::class;
        
        return $question->likes()
            ->where('user_id', $userId)
            ->where('user_type', $fullUserType)
            ->delete();
    }

    public function incrementLikesCount($questionId)
    {
        return $this->model->where('id', $questionId)->increment('likes_count');
    }

    public function decrementLikesCount($questionId)
    {
        return $this->model->where('id', $questionId)->decrement('likes_count');
    }
} 