<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function create(Request $request)
    {
        return $this->examService->createExam($request);
    }

    public function getBySection(Request $request, $sectionId)
    {
        return $this->examService->getExamsBySection($sectionId, $request);
    }

    public function update(Request $request, $examId)
    {
        return $this->examService->updateExam($request, $examId);
    }

    public function delete($examId)
    {
        return $this->examService->deleteExam($examId);
    }
} 