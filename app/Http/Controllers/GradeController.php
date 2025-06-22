<?php
namespace App\Http\Controllers;

use App\Services\GradeServices\CreateGradeService;
use App\Services\GradeServices\DisplayGradeService;
use App\Services\GradeServices\UpdateGradeService;
use App\Services\GradeServices\DeleteGradeService;
use App\Http\Requests\GradeRequest\CreateGradeRequest;
use App\Http\Requests\GradeRequest\UpdateGradeRequest;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    protected $createGradeService;
    protected $displayGradeService;
    protected $updateGradeService;
    protected $deleteGradeService;

    public function __construct(
        CreateGradeService $createGradeService,
        DisplayGradeService $displayGradeService,
        UpdateGradeService $updateGradeService,
        DeleteGradeService $deleteGradeService
    ) {
        $this->createGradeService = $createGradeService;
        $this->displayGradeService = $displayGradeService;
        $this->updateGradeService = $updateGradeService;
        $this->deleteGradeService = $deleteGradeService;
    }

    public function create(CreateGradeRequest $request)
    {
        return $this->createGradeService->create($request->validated());
    }

    public function getByExam($examId)
    {
        $perPage = request()->input('per_page', 10);
        return $this->displayGradeService->getGradesByExam($examId, $perPage);
    }

    public function getByStudent($studentId)
    {
        $perPage = request()->input('per_page', 10);
        return $this->displayGradeService->getGradesByStudent($studentId, $perPage);
    }

    public function update(UpdateGradeRequest $request, $gradeId)
    {
        return $this->updateGradeService->update($request->validated(), $gradeId);
    }

    public function delete($gradeId)
    {
        return $this->deleteGradeService->delete($gradeId);
    }

    public function getMyGrades()
    {
        $studentId = Auth::id();
        $perPage = request()->input('per_page', 10);
        return $this->displayGradeService->getGradesByStudent($studentId, $perPage);
    }
}
