<?php

namespace App\Http\Controllers;

use App\Services\GradeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    protected $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function create(Request $request)
    {
        return $this->gradeService->createGrade($request);
    }

    public function getByExam(Request $request, $examId)
    {
        return $this->gradeService->getGradesByExam($examId, $request);
    }

    public function getByStudent(Request $request, $studentId)
    {
        return $this->gradeService->getGradesByStudent($studentId, $request);
    }

    public function update(Request $request, $gradeId)
    {
        return $this->gradeService->updateGrade($request, $gradeId);
    }

    public function delete($gradeId)
    {
        return $this->gradeService->deleteGrade($gradeId);
    }
    public function getMyGrades(Request $request)
{
    $studentId = Auth::id(); 
    return $this->gradeService->getGradesByStudent($studentId, $request);
}

} 