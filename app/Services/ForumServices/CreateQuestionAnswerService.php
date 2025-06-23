<?php

namespace App\Services\ForumServices;

use App\Repositories\QuestionRepository;
use App\Repositories\AnswerRepository;

class CreateQuestionAnswerService
{
    protected $questionRepository;
    protected $answerRepository;

    public function __construct(QuestionRepository $questionRepository, AnswerRepository $answerRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
    }

    public function createQuestion(array $data)
    {
        $data['user_id'] = auth()->user()->id;
        $data['user_type'] = auth()->user() instanceof \App\Models\Student ? 'student' : 'trainer';
        return $this->questionRepository->createQuestion($data);
    }

    public function createAnswer(array $data)
    {
        $data['user_id'] = auth()->user()->id;
        $data['user_type'] = auth()->user() instanceof \App\Models\Student ? 'student' : 'trainer';
        return $this->answerRepository->createAnswer($data);
    }
}
