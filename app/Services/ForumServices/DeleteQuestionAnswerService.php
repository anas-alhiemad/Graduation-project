<?php

namespace App\Services\ForumServices;

use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;

class DeleteQuestionAnswerService
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

    public function deleteQuestion($questionId): bool
    {
        return $this->questionRepository->deleteQuestion($questionId);
    }

    public function deleteAnswer($answerId): bool
    {
        return $this->answerRepository->deleteAnswer($answerId);
    }
}
