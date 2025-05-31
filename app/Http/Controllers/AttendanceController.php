<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CourseSectionServices\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function markAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:course_sections,id',
            'student_id' => 'required|exists:students,id',
            'is_present' => 'required|boolean',
            'date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $attendance = $this->attendanceService->markAttendance(
                $request->section_id,
                $request->student_id,
                $request->is_present,
                $request->date
            );

            return response()->json([
                'message' => 'Attendance marked successfully',
                'data' => $attendance
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getSectionAttendance($sectionId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $attendance = $this->attendanceService->getSectionAttendance(
                $sectionId,
                $request->date
            );

            return response()->json([
                'data' => $attendance
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getStudentAttendance($studentId, $sectionId)
    {
        try {
            $attendance = $this->attendanceService->getStudentAttendance(
                $studentId,
                $sectionId
            );

            return response()->json([
                'data' => $attendance
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
} 