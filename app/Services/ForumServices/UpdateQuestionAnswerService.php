<?php

namespace App\Services\ForumServices;

use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;

class UpdateQuestionAnswerService
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

    public function updateQuestion($questionId, array $data)
    {
        return $this->questionRepository->updateQuestion($questionId, $data);
    }

    public function updateAnswer($answerId, array $data)
    {
        return $this->answerRepository->updateAnswer($answerId, $data);
    }
}
