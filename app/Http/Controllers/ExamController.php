<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExamRequest\CreateExamRequest;
use App\Http\Requests\ExamRequest\UpdateExamRequest;
use App\Services\ExamService\CreateExamService;
use App\Services\ExamService\DisplayExamService;
use App\Services\ExamService\UpdateExamService;
use App\Services\ExamService\DeleteExamService;

class ExamController extends Controller
{
    protected $createExamService;
    protected $displayExamService;
    protected $updateExamService;
    protected $deleteExamService;

    public function __construct(
        CreateExamService $createExamService,
        DisplayExamService $displayExamService,
        UpdateExamService $updateExamService,
        DeleteExamService $deleteExamService
    ) {
        $this->createExamService = $createExamService;
        $this->displayExamService = $displayExamService;
        $this->updateExamService = $updateExamService;
        $this->deleteExamService = $deleteExamService;
    }

    public function create(CreateExamRequest $request)
    {
        return $this->createExamService->handle($request);
    }

    public function getBySection(Request $request, $sectionId)
    {
        return $this->displayExamService->handle($sectionId, $request);
    }

    public function update(UpdateExamRequest $request, $examId)
    {
        return $this->updateExamService->handle($request, $examId);
    }

    public function delete($examId)
    {
        return $this->deleteExamService->handle($examId);
    }
}
