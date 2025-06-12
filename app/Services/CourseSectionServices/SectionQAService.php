<?php

namespace App\Services\CourseSectionServices;

use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;
use App\Models\Student;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class SectionQAService
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

    public function getSectionQuestions($sectionId)
    {
        return $this->questionRepository->getSectionQuestions($sectionId);
    }

    public function createQuestion($data)
    {
        $user = Auth::user();
        $userType = $user instanceof Student ? 'student' : 'trainer';

        $data['user_id'] = $user->id;
        $data['user_type'] = $userType === 'student' ? Student::class : Trainer::class;

        return $this->questionRepository->createQuestion($data);
    }

    public function updateQuestion($questionId, $data)
    {
        $question = $this->questionRepository->getQuestion($questionId);
        if (!$question) {
            return null;
        }

        $user = Auth::user();
        if ($question->user_id !== $user->id) {
            return null;
        }

        return $this->questionRepository->updateQuestion($questionId, $data);
    }

    public function deleteQuestion($questionId)
    {
        $question = $this->questionRepository->getQuestion($questionId);
        if (!$question) {
            return false;
        }

        $user = Auth::user();
        if ($question->user_id !== $user->id) {
            return false;
        }

        return $this->questionRepository->deleteQuestion($questionId);
    }

    public function getQuestionAnswers($questionId)
    {
        return $this->answerRepository->getQuestionAnswers($questionId);
    }

    public function createAnswer($data)
    {
        $user = Auth::user();
        $userType = $user instanceof Student ? 'student' : 'trainer';

        $data['user_id'] = $user->id;
        $data['user_type'] = $userType === 'student' ? Student::class : Trainer::class;

        return $this->answerRepository->createAnswer($data);
    }

    public function updateAnswer($answerId, $data)
    {
        $answer = $this->answerRepository->getAnswer($answerId);
        if (!$answer) {
            return null;
        }

        $user = Auth::user();
        if ($answer->user_id !== $user->id) {
            return null;
        }

        return $this->answerRepository->updateAnswer($answerId, $data);
    }

    public function deleteAnswer($answerId)
    {
        $answer = $this->answerRepository->getAnswer($answerId);
        if (!$answer) {
            return false;
        }

        $user = Auth::user();
        if ($answer->user_id !== $user->id) {
            return false;
        }

        return $this->answerRepository->deleteAnswer($answerId);
    }

    public function likeQuestion($questionId)
    {
        $question = $this->questionRepository->getQuestion($questionId);
        if (!$question) {
            return null;
        }

        $user = Auth::user();
        $userType = $user instanceof Student ? 'student' : 'trainer';
        $fullUserType = $userType === 'student' ? Student::class : Trainer::class;

        $existingLike = $question->likes()
            ->where('user_id', $user->id)
            ->where('user_type', $fullUserType)
            ->first();

        if ($existingLike) {
            return null;
        }

        $like = $this->questionRepository->addLike($questionId, $user->id, $userType);
        if ($like) {
            $this->questionRepository->incrementLikesCount($questionId);
        }

        return $like;
    }

    public function unlikeQuestion($questionId)
    {
        $question = $this->questionRepository->getQuestion($questionId);
        if (!$question) {
            return false;
        }

        $user = Auth::user();
        $userType = $user instanceof Student ? 'student' : 'trainer';

        $removed = $this->questionRepository->removeLike($questionId, $user->id, $userType);
        if ($removed) {
            $this->questionRepository->decrementLikesCount($questionId);
        }

        return $removed;
    }

    public function likeAnswer($answerId)
    {
        $answer = $this->answerRepository->getAnswer($answerId);
        if (!$answer) {
            return null;
        }

        $user = Auth::user();
        $userType = $user instanceof Student ? 'student' : 'trainer';
        $fullUserType = $userType === 'student' ? Student::class : Trainer::class;

        $existingLike = $answer->likes()
            ->where('user_id', $user->id)
            ->where('user_type', $fullUserType)
            ->first();

        if ($existingLike) {
            return null;
        }

        $like = $this->answerRepository->addLike($answerId, $user->id, $userType);
        if ($like) {
            $this->answerRepository->incrementLikesCount($answerId);
        }

        return $like;
    }

    public function unlikeAnswer($answerId)
    {
        $answer = $this->answerRepository->getAnswer($answerId);
        if (!$answer) {
            return false;
        }

        $user = Auth::user();
        $userType = $user instanceof Student ? 'student' : 'trainer';

        $removed = $this->answerRepository->removeLike($answerId, $user->id, $userType);
        if ($removed) {
            $this->answerRepository->decrementLikesCount($answerId);
        }

        return $removed;
    }

    public function acceptAnswer($answerId)
    {
        $answer = $this->answerRepository->getAnswer($answerId);
        if (!$answer) {
            return false;
        }

        $question = $this->questionRepository->getQuestion($answer->question_id);
        if (!$question) {
            return false;
        }

       

        return $this->answerRepository->acceptAnswer($answerId);
    }

    public function unacceptAnswer($answerId)
    {
        $answer = $this->answerRepository->getAnswer($answerId);
        if (!$answer) {
            return false;
        }

        $question = $this->questionRepository->getQuestion($answer->question_id);
        if (!$question) {
            return false;
        }

       
        return $this->answerRepository->unacceptAnswer($answerId);
    }
}