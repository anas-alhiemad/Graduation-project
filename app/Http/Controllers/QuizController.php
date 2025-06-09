<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizServices\CreateQuizService;
use App\Http\Requests\QuizRequest\CreateQuizRequest;

class QuizController extends Controller
{
    protected $createQuizService;

    public function __construct(CreateQuizService $createQuizService)
    {
        $this->createQuizService = $createQuizService;
    }


    public function CreateQuiz(CreateQuizRequest $request) 
    {
        return $this->createQuizService->store($request);
    }
}
