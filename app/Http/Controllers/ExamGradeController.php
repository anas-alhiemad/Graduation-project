<?php

namespace App\Http\Controllers;

use App\Services\ExamGradeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function getStudentGrades($studentId): JsonResponse
    {
        try {
            //   For students, ensure they can only access their own grades
            if (auth()->user()->hasRole('student') && auth()->user()->id != $studentId) {
                return response()->json(['message' => 'Unauthorized access to student grades'], 403);
            }

            $grades = $this->examGradeService->getStudentGrades(1);
            return response()->json(['data' => $grades]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

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