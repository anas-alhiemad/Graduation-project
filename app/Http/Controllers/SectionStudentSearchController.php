<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Services\CourseSectionServices\SectionStudentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SectionStudentSearchController extends Controller
{
    protected $sectionStudentService;

    public function __construct(SectionStudentService $sectionStudentService)
    {
        $this->sectionStudentService = $sectionStudentService;
    }

    public function searchInAllSections(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'search_term' => 'required|string|min:2'
            ]);

            $students = $this->sectionStudentService->searchStudentsInTrainerSections($validated['search_term']);
            
            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function searchInSpecificSection(Request $request, CourseSection $section): JsonResponse
    {
        try {
            $validated = $request->validate([
                'search_term' => 'required|string|min:2'
            ]);

            $students = $this->sectionStudentService->searchStudentsInSpecificSection($section, $validated['search_term']);
            
            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function getStudentDetails(Request $request, CourseSection $section, int $studentId): JsonResponse
    {
        try {
            $student = \App\Models\Student::findOrFail($studentId);
            $details = $this->sectionStudentService->getStudentDetailsInSection($section, $student);
            
            return response()->json([
                'success' => true,
                'data' => $details
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
} 