<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\adminServices\PointsManagementService;
use Illuminate\Http\JsonResponse;

class PointsManagementController extends Controller
{
    protected $pointsService;

    public function __construct(PointsManagementService $pointsService)
    {
        $this->pointsService = $pointsService;
    }

    public function getTopStudents(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);
        $students = $this->pointsService->getTopStudents($limit);
        return response()->json(['data' => $students]);
    }

    public function getTopSecretaries(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);
        $secretaries = $this->pointsService->getTopSecretaries($limit);
        return response()->json(['data' => $secretaries]);
    }

    public function updateStudentPoints(Request $request, $studentId): JsonResponse
    {
        $request->validate([
            'points' => 'required|integer'
        ]);

        $student = $this->pointsService->updateStudentPoints($studentId, $request->points);
        return response()->json([
            'message' => 'Student points updated successfully',
            'data' => $student
        ]);
    }

    public function updateSecretaryPoints(Request $request, $secretaryId): JsonResponse
    {
        $request->validate([
            'points' => 'required|integer'
        ]);

        $secretary = $this->pointsService->updateSecretaryPoints($secretaryId, $request->points);
        return response()->json([
            'message' => 'Secretary points updated successfully',
            'data' => $secretary
        ]);
    }
} 