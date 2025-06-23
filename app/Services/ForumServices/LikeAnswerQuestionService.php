<?php

namespace App\Services\ForumServices;

use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;
use Illuminate\Support\Facades\Auth;

class LikeAnswerQuestionService
{
    protected $questionRepository;
    protected $answerRepository;

    public function __construct(
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    protected function getUserInfo()
    {
        $user = Auth::user();
        $userType = class_basename($user); // Student أو Trainer
        return [$user->id, strtolower($userType)];
    }

    // ===== Question Likes =====

    public function likeQuestion($questionId)
    {
        [$userId, $userType] = $this->getUserInfo();

        $like = $this->questionRepository->addLike($questionId, $userId, $userType);
        if ($like) {
            $this->questionRepository->incrementLikesCount($questionId);
        }

        return $like;
    }

    public function unlikeQuestion($questionId)
    {
        [$userId, $userType] = $this->getUserInfo();

        $removed = $this->questionRepository->removeLike($questionId, $userId, $userType);
        if ($removed) {
            $this->questionRepository->decrementLikesCount($questionId);
        }

        return $removed;
    }

    // ===== Answer Likes =====

    public function likeAnswer($answerId)
    {
        [$userId, $userType] = $this->getUserInfo();

        $like = $this->answerRepository->addLike($answerId, $userId, $userType);
        if ($like) {
            $this->answerRepository->incrementLikesCount($answerId);
        }

        return $like;
    }

    public function unlikeAnswer($answerId)
    {
        [$userId, $userType] = $this->getUserInfo();

        $removed = $this->answerRepository->removeLike($answerId, $userId, $userType);
        if ($removed) {
            $this->answerRepository->decrementLikesCount($answerId);
        }

        return $removed;
    }

    // ===== Accept / Unaccept Answer =====

    public function acceptAnswer($answerId)
    {
        return $this->answerRepository->acceptAnswer($answerId);
    }

    public function unacceptAnswer($answerId)
    {
        return $this->answerRepository->unacceptAnswer($answerId);
    }
}
