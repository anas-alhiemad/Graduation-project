<?php

namespace App\Services\ForumServices;

use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;

class DisplayForumService
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

    public function getQuestionAnswers($questionId)
    {
        return $this->answerRepository->getQuestionAnswers($questionId);
    }
}
