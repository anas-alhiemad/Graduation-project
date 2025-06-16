<?php

namespace App\Http\Controllers;

use App\Services\SessionAttendanceService;
use Illuminate\Http\Request;

class SessionAttendanceController extends Controller
{
    protected $sessionAttendanceService;

    public function __construct(SessionAttendanceService $sessionAttendanceService)
    {
        $this->sessionAttendanceService = $sessionAttendanceService;
    }

    public function markAttendance(Request $request, $sessionId)
    {
        return $this->sessionAttendanceService->markAttendance($request, $sessionId);
    }

    public function getSessionAttendance($sessionId)
    {
        return $this->sessionAttendanceService->getSessionAttendance($sessionId);
    }

    public function getStudentAttendance(Request $request, $studentId)
    {
        return $this->sessionAttendanceService->getStudentAttendance($studentId, $request);
    }

    public function getSectionAttendance(Request $request, $sectionId)
    {
        return $this->sessionAttendanceService->getSectionAttendance($sectionId, $request);
    }

    public function editAttendance(Request $request, $attendanceId)
    {
        return $this->sessionAttendanceService->editAttendance($request, $attendanceId);
    }

    public function deleteAttendance($attendanceId)
    {
        return $this->sessionAttendanceService->deleteAttendance($attendanceId);
    }

    public function getStudentAttendanceStats($studentId, $sectionId)
    {
        return $this->sessionAttendanceService->getStudentAttendanceStats($studentId, $sectionId);
    }

    public function getSectionAttendanceStats($sectionId)
    {
        return $this->sessionAttendanceService->getSectionAttendanceStats($sectionId);
    }
} 