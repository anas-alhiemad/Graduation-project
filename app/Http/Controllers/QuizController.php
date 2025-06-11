<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizServices\CreateQuizService;
use App\Services\QuizServices\DeleteQuizService;
use App\Services\QuizServices\UpdateQuizService;
use App\Services\QuizServices\DisplayQuizService;
use App\Http\Requests\QuizRequest\CreateQuizRequest;
use App\Http\Requests\QuizRequest\UpdateTitleQuizRequest;
use App\Http\Requests\QuizRequest\UpdateQuestionQuizRequest;

class QuizController extends Controller
{
    protected $createQuizService;
    protected $displayService;
    protected $deleteService;
    protected $updateService;

    public function __construct(CreateQuizService $createQuizService,DisplayQuizService $displayService,DeleteQuizService $deleteService,UpdateQuizService $updateService)
    {
        $this->createQuizService = $createQuizService;
        $this->displayService = $displayService;
        $this->deleteService = $deleteService;
        $this->updateService = $updateService;
    }


    public function CreateQuiz(CreateQuizRequest $request) 
    {
        return $this->createQuizService->store($request);
    }


    public function ShowQuizById($quiz_id)
    {
        return $this->displayService->showById($quiz_id);
    }

    public function ListQuizzesBySectionId($course_section_id)
    {
        return $this->displayService->listByIdSection($course_section_id);
    }

    public function DeleteQuiz($quiz_id)
    {
        return $this->deleteService->delete($quiz_id);
    }

    public function DeleteQuizQuestion($question_id)
    {
        return $this->deleteService->deleteQuestion($question_id);
    }

    public function UpdateTitle(UpdateTitleQuizRequest $request, $quiz_id)
    {

        return $this->updateService->updateTitle($quiz_id, $request);
    }


    public function UpdateQuestion(UpdateQuestionQuizRequest $request, $question_id)
    {
        return $this->updateService->updateQuestion($question_id, $request);
    }


    public function answerQuestion($option_id)
    {
        return $this->displayService->answerQuestion($option_id); 
    }
}
