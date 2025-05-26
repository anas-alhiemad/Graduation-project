<?php

namespace App\Http\Controllers;

use App\Services\ExamGradeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

   use App\Models\Trainer;
class ExamGradeController extends Controller
{
    protected $examGradeService;

    public function __construct(ExamGradeService $examGradeService)
    {
        $this->examGradeService = $examGradeService;
    }

    public function index(): JsonResponse
    {
        try {
            $examGrades = $this->examGradeService->getAllExamGrades();
            return response()->json(['data' => $examGrades]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $examGrade = $this->examGradeService->createExamGrade($request->all());
            return response()->json(['data' => $examGrade], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $examGrade = $this->examGradeService->getExamGrade($id);
            return response()->json(['data' => $examGrade]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $examGrade = $this->examGradeService->updateExamGrade($id, $request->all());
            return response()->json(['data' => $examGrade]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->examGradeService->deleteExamGrade($id);
          //  return response()->json(null, 204);
           return response()->json(['message' => 'Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


//for trainer
public function getStudentGrades($studentId): JsonResponse
{
    try {
        $user = auth()->user();

        // إذا كان المستخدم ليس مدرس لا تسمح له ا
        if (!($user instanceof Trainer)) {
            return response()->json(['message' => 'Unauthorized: only trainers can access student grades'], 403);
        }

        
        $grades = $this->examGradeService->getStudentGrades($studentId);

        return response()->json(['data' => $grades]);

    } catch (\InvalidArgumentException $e) {
        return response()->json(['message' => $e->getMessage()], 403);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}


//for student 
    public function getMyGrades(): JsonResponse
    {
        try {
           

            $studentId = auth()->user()->id;
            $grades = $this->examGradeService->getStudentGrades($studentId);
            return response()->json([
                'message' => 'Your exam grades retrieved successfully',
                'data' => $grades
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getSectionGrades($sectionId): JsonResponse
    {
        try {
            $grades = $this->examGradeService->getSectionGrades($sectionId);
            return response()->json(['data' => $grades]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getTrainerGrades($trainerId): JsonResponse
    {
        try {
            $grades = $this->examGradeService->getTrainerGrades($trainerId);
            return response()->json(['data' => $grades]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getSectionStatistics($sectionId): JsonResponse
    {
        try {
            $statistics = $this->examGradeService->getSectionStatistics($sectionId);
            return response()->json(['data' => $statistics]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
} 